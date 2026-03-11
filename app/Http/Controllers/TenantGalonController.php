<?php

namespace App\Http\Controllers;

use App\Models\PesananGalon;
use App\Models\GalonKatalog;
use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Pesan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantGalonController extends Controller
{
    /**
     * Display tenant's galon order history.
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

        // Get all galon orders by this tenant
        $query = PesananGalon::with(['galonType', 'kost', 'pembayaran'])
            ->where('id_penyewa', $tenant->id_user)
            ->orderBy('created_at', 'desc');

        // Filter by status if specified
        if ($status) {
            $query->where('status_galon', $status);
        }

        $orders = $query->paginate(15);

        return view('tenant.galon.index', compact('orders', 'activePesanan', 'status'));
    }

    /**
     * Show the form for creating a new galon order.
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
            return redirect()->route('galon.catalog')
                ->with('error', 'Anda harus memiliki pemesanan kost aktif untuk memesan galon.');
        }

        $kost = $activePesanan->kamar->kost;

        // Get available galon types for this kost
        $galonTypes = GalonKatalog::where('id_kost', $kost->id_kost)
            ->where('is_available', true)
            ->get();

        return view('tenant.galon.create', compact('galonTypes', 'kost'));
    }

    /**
     * Store a newly created galon order in storage.
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
                ->with('error', 'Anda harus memiliki pemesanan kost aktif untuk memesan galon.');
        }

        $validated = $request->validate([
            'id_galon_tipe' => 'required|exists:galon_katalog,id_galon_tipe',
            'foto_kosong' => 'required|image|max:2048', // Max 2MB
        ], [
            'foto_kosong.required' => 'Foto galon kosong wajib diunggah.',
            'foto_kosong.image' => 'File harus berupa gambar.',
            'foto_kosong.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $galonType = GalonKatalog::where('id_galon_tipe', $validated['id_galon_tipe'])
            ->where('id_kost', $activePesanan->kamar->id_kost)
            ->where('is_available', true)
            ->first();

        if (!$galonType) {
            return redirect()->back()
                ->with('error', 'Jenis air tidak tersedia.');
        }

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto_kosong')) {
            $fotoPath = $request->file('foto_kosong')->store('galon/kosong', 'public');
        }

        // Create the order
        DB::beginTransaction();
        try {
            $order = PesananGalon::create([
                'id_penyewa' => $tenant->id_user,
                'id_kost' => $activePesanan->kamar->id_kost,
                'id_galon_tipe' => $galonType->id_galon_tipe,
                'foto_kosong' => $fotoPath,
                'total_bayar' => $galonType->harga,
                'status_galon' => PesananGalon::STATUS_MENUNGGU_BAYAR,
            ]);

            DB::commit();

            return redirect()->route('galon.orders.show', $order->id_order_galon)
                ->with('success', 'Pesanan galon berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified galon order.
     */
    public function show(PesananGalon $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['galonType', 'kost', 'pembayaran']);

        return view('tenant.galon.show', compact('order'));
    }

    /**
     * Cancel the specified galon order.
     */
    public function cancel(PesananGalon $order)
    {
        // Only allow tenant to cancel their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow cancellation if status is menunggu_bayar
        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        $order->update(['status_galon' => PesananGalon::STATUS_DIBATALKAN]);

        return redirect()->route('galon.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Mark the order as completed (confirm receipt).
     */
    public function complete(PesananGalon $order)
    {
        // Only allow tenant to complete their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow completion if status is diambil
        if ($order->status_galon !== PesananGalon::STATUS_DIAMBIL) {
            return redirect()->back()
                ->with('error', 'Pesanan belum dapat ditandai sebagai selesai.');
        }

        $order->update(['status_galon' => PesananGalon::STATUS_SELESAI]);

        return redirect()->route('galon.orders.index')
            ->with('success', 'Pesanan berhasil dikonfirmasi sebagai selesai.');
    }

    /**
     * Process payment for galon order via Midtrans.
     */
    public function pay(PesananGalon $order)
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
        $pembayaran = Pembayaran::where('id_pesan', $order->id_order_galon)
            ->where('tipe_pembayaran', 'galon')
            ->first();

        if (!$pembayaran) {
            // Create payment record
            $pembayaran = Pembayaran::create([
                'id_pesan' => $order->id_order_galon,
                'tipe_pembayaran' => 'galon',
                'orderan_id' => $order->orderan_id,
                'order_id' => $order->orderan_id,
                'jenis_pembayaran' => null,
                'jumlah_bayar' => $order->total_bayar,
                'tanggal_bayar' => now(),
                'transaction_status' => 'pending',
            ]);
        }

        // Check if payment was already settled via callback
        if ($pembayaran->transaction_status === 'settlement' || $pembayaran->transaction_status === 'capture') {
            // Update order status
            $order->update(['status_galon' => PesananGalon::STATUS_DIPROSES]);
            return redirect()->route('galon.orders.payment.success', $order->id_order_galon);
        }

        // Generate snap token
        $snapToken = $pembayaran->generateSnapToken();

        if (!$snapToken) {
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan token pembayaran. Silakan coba lagi.');
        }

        return view('tenant.galon.payment', compact('order', 'pembayaran', 'snapToken'));
    }

    /**
     * Payment success callback.
     */
    public function paymentSuccess(PesananGalon $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['galonType', 'pembayaran']);

        return view('tenant.galon.payment-success', compact('order'));
    }

    /**
     * Payment failed callback.
     */
    public function paymentFailed(PesananGalon $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['galonType', 'pembayaran']);

        return view('tenant.galon.payment-failed', compact('order'));
    }
}
