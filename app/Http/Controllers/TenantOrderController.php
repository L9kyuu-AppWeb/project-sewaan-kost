<?php

namespace App\Http\Controllers;

use App\Models\PesananMakananHeader;
use App\Models\PesananMakananDetail;
use App\Models\Makanan;
use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Pesan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenantOrderController extends Controller
{
    /**
     * Display tenant's food order history.
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

        // Check if makanan feature is enabled for their kost
        if ($activePesanan && $activePesanan->kamar) {
            if (!$activePesanan->kamar->kost->isFeatureEnabled('makanan')) {
                return redirect()->route('dashboard.penyewa')
                    ->with('error', 'Fitur makanan belum diaktifkan oleh pengelola kost.');
            }
        }

        // Get all food order headers by this tenant
        $query = PesananMakananHeader::with(['details.makanan', 'kost', 'latestPembayaran'])
            ->where('id_penyewa', $tenant->id_user)
            ->orderBy('created_at', 'desc');

        // Filter by status if specified
        if ($status) {
            $query->where('status_antar', $status);
        }

        $orders = $query->paginate(15);

        return view('tenant.orders.index', compact('orders', 'activePesanan', 'status'));
    }

    /**
     * Show the food menu for ordering (cart page).
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
            return redirect()->route('dashboard.penyewa')
                ->with('error', 'Anda harus memiliki pemesanan kost aktif untuk memesan makanan.');
        }

        // Check if makanan feature is enabled
        if (!$activePesanan->kamar->kost->isFeatureEnabled('makanan')) {
            return redirect()->route('dashboard.penyewa')
                ->with('error', 'Fitur makanan belum diaktifkan oleh pengelola kost.');
        }

        $kost = $activePesanan->kamar->kost;

        // Get available food menus
        $makanans = Makanan::where('id_kost', $kost->id_kost)
            ->where('is_available', true)
            ->where('stok', '>', 0)
            ->get();

        return view('tenant.orders.create', compact('makanans', 'kost'));
    }

    /**
     * Store a newly created food order with multiple items in storage.
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
                ->with('error', 'Anda harus memiliki pemesanan kost aktif untuk memesan makanan.');
        }

        // Debug logging
        \Log::info('Order Request', ['items' => $request->items]);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_makanan' => 'required|exists:makanan,id_makanan',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan_item' => 'nullable|string|max:255',
            'catatan_pesanan' => 'nullable|string|max:500',
        ], [
            'items.required' => 'Silakan pilih minimal 1 menu untuk dipesan.',
            'items.array' => 'Format pesanan tidak valid.',
            'items.min' => 'Silakan pilih minimal 1 menu untuk dipesan.',
        ]);

        \Log::info('Validated Items', ['validated' => $validated]);

        $kostId = $activePesanan->kamar->id_kost;
        $totalHarga = 0;
        $totalItem = 0;
        $itemsToProcess = [];

        // Validate and calculate total
        foreach ($validated['items'] as $index => $item) {
            \Log::info("Processing item {$index}", ['item' => $item]);
            
            $makanan = Makanan::where('id_makanan', $item['id_makanan'])
                ->where('id_kost', $kostId)
                ->where('is_available', true)
                ->where('stok', '>=', $item['jumlah'])
                ->first();

            if (!$makanan) {
                \Log::error("Menu {$item['id_makanan']} not found or not available");
                return redirect()->back()
                    ->with('error', 'Menu tidak tersedia atau stok tidak mencukupi.')
                    ->withInput();
            }

            $subtotal = $makanan->harga * $item['jumlah'];
            $totalHarga += $subtotal;
            $totalItem += $item['jumlah'];

            $itemsToProcess[] = [
                'makanan' => $makanan,
                'jumlah' => $item['jumlah'],
                'catatan_item' => $item['catatan_item'] ?? null,
                'subtotal' => $subtotal,
            ];
        }

        \Log::info('Order Summary', ['totalHarga' => $totalHarga, 'totalItem' => $totalItem]);

        // Create the order
        DB::beginTransaction();
        try {
            // Create header
            $order = PesananMakananHeader::create([
                'id_penyewa' => $tenant->id_user,
                'id_kost' => $kostId,
                'total_harga' => $totalHarga,
                'total_item' => $totalItem,
                'status_antar' => PesananMakananHeader::STATUS_MENUNGGU_BAYAR,
                'catatan' => $validated['catatan_pesanan'] ?? null,
            ]);

            \Log::info('Order created', ['order_id' => $order->id_pesanan_makanan]);

            // Create details and reduce stock
            foreach ($itemsToProcess as $item) {
                PesananMakananDetail::create([
                    'id_pesanan_makanan' => $order->id_pesanan_makanan,
                    'id_makanan' => $item['makanan']->id_makanan,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['makanan']->harga,
                    'subtotal' => $item['subtotal'],
                    'catatan_item' => $item['catatan_item'],
                ]);

                // Reduce stock
                $item['makanan']->decrement('stok', $item['jumlah']);
            }

            DB::commit();

            \Log::info('Order completed', ['order_id' => $order->id_pesanan_makanan]);

            return redirect()->route('orders.show', $order->id_pesanan_makanan)
                ->with('success', 'Pesanan makanan berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified food order.
     */
    public function show(PesananMakananHeader $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['details.makanan', 'kost', 'latestPembayaran']);

        return view('tenant.orders.show', compact('order'));
    }

    /**
     * Cancel the specified food order (only if status is menunggu_bayar).
     */
    public function cancel(PesananMakananHeader $order)
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

        // Update status (stock will be restored via model event)
        $order->update(['status_antar' => PesananMakananHeader::STATUS_DIBATALKAN]);

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Mark the order as completed (confirm receipt).
     */
    public function complete(PesananMakananHeader $order)
    {
        // Only allow tenant to complete their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow completion if status is dikirim or diproses
        if (!$order->canBeCompleted()) {
            return redirect()->back()
                ->with('error', 'Pesanan belum dapat ditandai sebagai selesai.');
        }

        $order->update(['status_antar' => PesananMakananHeader::STATUS_SELESAI]);

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dikonfirmasi sebagai selesai.');
    }

    /**
     * Process payment for food order via Midtrans.
     */
    public function pay(PesananMakananHeader $order)
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
        $pembayaran = Pembayaran::where('id_pesan', $order->id_pesanan_makanan)
            ->where('tipe_pembayaran', 'makanan')
            ->first();

        if (!$pembayaran) {
            // Create payment record
            $pembayaran = Pembayaran::create([
                'id_pesan' => $order->id_pesanan_makanan,
                'tipe_pembayaran' => 'makanan',
                'orderan_id' => $order->orderan_id,
                'order_id' => $order->orderan_id, // Set order_id to match orderan_id for Midtrans
                'jenis_pembayaran' => null, // Will be updated by Midtrans callback
                'jumlah_bayar' => $order->total_harga,
                'tanggal_bayar' => now(),
                'transaction_status' => 'pending',
            ]);
        }

        // Check if payment was already settled via callback
        if ($pembayaran->transaction_status === 'settlement' || $pembayaran->transaction_status === 'capture') {
            // Update order status
            $order->update(['status_antar' => PesananMakananHeader::STATUS_DIPROSES]);
            return redirect()->route('orders.payment.success', $order->id_pesanan_makanan);
        }

        // Generate snap token
        $snapToken = $pembayaran->generateSnapToken();

        if (!$snapToken) {
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan token pembayaran. Silakan coba lagi.');
        }

        return view('tenant.orders.payment', compact('order', 'pembayaran', 'snapToken'));
    }

    /**
     * Payment success callback.
     */
    public function paymentSuccess(PesananMakananHeader $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['details.makanan', 'latestPembayaran']);

        return view('tenant.orders.payment-success', compact('order'));
    }

    /**
     * Payment failed callback.
     */
    public function paymentFailed(PesananMakananHeader $order)
    {
        // Only allow tenant to view their own orders
        if ($order->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['details.makanan', 'latestPembayaran']);

        return view('tenant.orders.payment-failed', compact('order'));
    }
}
