<?php

namespace App\Http\Controllers;

use App\Models\PesananLaundry;
use App\Models\LaundryKatalog;
use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Pesan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantLaundryController extends Controller
{
    /**
     * Display tenant's laundry order history.
     */
    public function index(Request $request)
    {
        $tenant = Auth::user();
        $status = $request->query('status');

        // Get tenant's active booking to determine their kost
        $activePesanan = Pesan::where('id_penyewa', $tenant->id_user)
            ->whereIn('status_pesan', [Pesan::STATUS_AKTIF, Pesan::STATUS_PROSES_VERIFIKASI])
            ->with('kamar.kost')
            ->first();

        // Get all laundry orders by this tenant
        $query = PesananLaundry::with(['laundryType', 'kost', 'pembayaran'])
            ->where('id_penyewa', $tenant->id_user)
            ->orderBy('created_at', 'desc');

        // Filter by status if specified
        if ($status) {
            $query->where('status_laundry', $status);
        }

        $orders = $query->paginate(15);

        return view('tenant.laundry.index', compact('orders', 'activePesanan', 'status'));
    }

    /**
     * Show the form for creating a new laundry order.
     */
    public function create()
    {
        $tenant = Auth::user();

        // Get tenant's active booking
        $activePesanan = Pesan::where('id_penyewa', $tenant->id_user)
            ->whereIn('status_pesan', [Pesan::STATUS_AKTIF, Pesan::STATUS_PROSES_VERIFIKASI])
            ->with('kamar.kost')
            ->first();

        if (!$activePesanan || !$activePesanan->kamar) {
            return redirect()->route('laundry.orders.index')
                ->with('error', 'Anda harus memiliki pemesanan kost aktif untuk memesan laundry.');
        }

        $kost = $activePesanan->kamar->kost;

        // Get available laundry types for this kost
        $laundryTypes = LaundryKatalog::where('id_kost', $kost->id_kost)
            ->where('is_available', true)
            ->get();

        return view('tenant.laundry.create', compact('laundryTypes', 'kost'));
    }

    /**
     * Store a newly created laundry order in storage.
     */
    public function store(Request $request)
    {
        $tenant = Auth::user();

        // Verify tenant has active booking
        $activePesanan = Pesan::where('id_penyewa', $tenant->id_user)
            ->whereIn('status_pesan', [Pesan::STATUS_AKTIF, Pesan::STATUS_PROSES_VERIFIKASI])
            ->with('kamar.kost')
            ->first();

        if (!$activePesanan || !$activePesanan->kamar) {
            return redirect()->back()
                ->with('error', 'Anda harus memiliki pemesanan kost aktif untuk memesan laundry.');
        }

        $validated = $request->validate([
            'id_laundry_tipe' => 'required|exists:laundry_katalog,id_laundry_tipe',
            'foto_awal' => 'required|image|max:2048', // Max 2MB
        ], [
            'foto_awal.required' => 'Foto pakaian wajib diunggah.',
            'foto_awal.image' => 'File harus berupa gambar.',
            'foto_awal.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $laundryType = LaundryKatalog::where('id_laundry_tipe', $validated['id_laundry_tipe'])
            ->where('id_kost', $activePesanan->kamar->id_kost)
            ->where('is_available', true)
            ->first();

        if (!$laundryType) {
            return redirect()->back()
                ->with('error', 'Layanan laundry tidak tersedia.');
        }

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto_awal')) {
            $fotoPath = $request->file('foto_awal')->store('laundry/awal', 'public');
        }

        // Create the order
        DB::beginTransaction();
        try {
            $order = PesananLaundry::create([
                'id_penyewa' => $tenant->id_user,
                'id_kost' => $activePesanan->kamar->id_kost,
                'id_laundry_tipe' => $laundryType->id_laundry_tipe,
                'foto_awal' => $fotoPath,
                'status_laundry' => PesananLaundry::STATUS_MENUNGGU_JEMPUT,
            ]);

            DB::commit();

            return redirect()->route('laundry.orders.show', $order->id_order_laundry)
                ->with('success', 'Pesanan laundry berhasil dibuat! Owner akan segera menjemput pakaian Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified laundry order.
     */
    public function show(PesananLaundry $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['laundryType', 'kost', 'pembayaran']);

        return view('tenant.laundry.show', compact('order'));
    }

    /**
     * Cancel the specified laundry order.
     */
    public function cancel(PesananLaundry $order)
    {
        // Only allow tenant to cancel their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow cancellation if status is menunggu_jemput or menunggu_bayar
        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        $order->update(['status_laundry' => PesananLaundry::STATUS_DIBATALKAN]);

        return redirect()->route('laundry.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Mark the order as completed (confirm receipt).
     */
    public function complete(PesananLaundry $order)
    {
        // Only allow tenant to complete their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow completion if status is siap_antar
        if ($order->status_laundry !== PesananLaundry::STATUS_Siap_ANTAR) {
            return redirect()->back()
                ->with('error', 'Pesanan belum dapat ditandai sebagai selesai.');
        }

        $order->update(['status_laundry' => PesananLaundry::STATUS_SELESAI]);

        return redirect()->route('laundry.orders.index')
            ->with('success', 'Pesanan berhasil dikonfirmasi sebagai selesai.');
    }

    /**
     * Process payment for laundry order via Midtrans.
     */
    public function pay(PesananLaundry $order)
    {
        // Only allow tenant to pay their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow payment if status is menunggu_bayar
        if (!$order->isPendingPayment()) {
            return redirect()->back()
                ->with('error', 'Pesanan sudah dibayar atau tidak dapat dibayar.');
        }

        // Check if payment record exists
        $pembayaran = Pembayaran::where('id_pesan', $order->id_order_laundry)
            ->where('tipe_pembayaran', 'laundry')
            ->first();

        if (!$pembayaran) {
            // Create payment record
            $pembayaran = Pembayaran::create([
                'id_pesan' => $order->id_order_laundry,
                'tipe_pembayaran' => 'laundry',
                'orderan_id' => $order->orderan_id,
                'order_id' => $order->orderan_id,
                'jenis_pembayaran' => null,
                'jumlah_bayar' => $order->total_harga,
                'tanggal_bayar' => now(),
                'transaction_status' => 'pending',
            ]);
        }

        // Check if payment was already settled via callback
        if ($pembayaran->transaction_status === 'settlement' || $pembayaran->transaction_status === 'capture') {
            // Update order status
            $order->update(['status_laundry' => PesananLaundry::STATUS_SEDANG_DICUCI]);
            return redirect()->route('laundry.orders.payment.success', $order->id_order_laundry);
        }

        // Generate snap token
        $snapToken = $pembayaran->generateSnapToken();

        if (!$snapToken) {
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan token pembayaran. Silakan coba lagi.');
        }

        return view('tenant.laundry.payment', compact('order', 'pembayaran', 'snapToken'));
    }

    /**
     * Payment success callback.
     */
    public function paymentSuccess(PesananLaundry $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['laundryType', 'pembayaran']);

        return view('tenant.laundry.payment-success', compact('order'));
    }

    /**
     * Payment failed callback.
     */
    public function paymentFailed(PesananLaundry $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['laundryType', 'pembayaran']);

        return view('tenant.laundry.payment-failed', compact('order'));
    }
}
