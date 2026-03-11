<?php

namespace App\Http\Controllers;

use App\Models\PesananMakananHeader;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerOrderController extends Controller
{
    /**
     * Display all food orders for owner's kosts.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');
        $status = $request->query('status');

        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();

        // Get orders filtered by kost and status
        $query = PesananMakananHeader::with(['penyewa', 'details.makanan', 'kost'])
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });

        if ($kostId) {
            $query->where('id_kost', $kostId);
        }

        if ($status) {
            $query->where('status_antar', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('owner.orders.index', compact('orders', 'kosts', 'kostId', 'status'));
    }

    /**
     * Display the specified food order.
     */
    public function show(PesananMakananHeader $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['penyewa', 'details.makanan', 'latestPembayaran']);

        return view('owner.orders.show', compact('order'));
    }

    /**
     * Update the order status to 'diproses'.
     */
    public function process(PesananMakananHeader $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow processing if status is menunggu_bayar
        if ($order->status_antar !== PesananMakananHeader::STATUS_MENUNGGU_BAYAR) {
            return redirect()->back()
                ->with('error', 'Pesanan sudah diproses atau memiliki status lain.');
        }

        $order->update(['status_antar' => PesananMakananHeader::STATUS_DIPROSES]);

        return redirect()->route('owner.orders.index')
            ->with('success', 'Pesanan berhasil ditandai sebagai diproses.');
    }

    /**
     * Update the order status to 'dikirim'.
     */
    public function deliver(PesananMakananHeader $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow delivery if status is diproses
        if ($order->status_antar !== PesananMakananHeader::STATUS_DIPROSES) {
            return redirect()->back()
                ->with('error', 'Pesanan harus diproses sebelum dikirim.');
        }

        $order->update(['status_antar' => PesananMakananHeader::STATUS_DIKIRIM]);

        return redirect()->route('owner.orders.index')
            ->with('success', 'Pesanan berhasil ditandai sebagai dikirim.');
    }

    /**
     * Cancel the order (for owner).
     */
    public function cancel(PesananMakananHeader $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow cancellation if status is menunggu_bayar or diproses
        if (!in_array($order->status_antar, [PesananMakananHeader::STATUS_MENUNGGU_BAYAR, PesananMakananHeader::STATUS_DIPROSES])) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $order->update(['status_antar' => PesananMakananHeader::STATUS_DIBATALKAN]);

        return redirect()->route('owner.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Get order statistics for dashboard.
     */
    public function stats()
    {
        $kostIds = Kost::where('id_pemilik', Auth::id())->pluck('id_kost');

        $stats = [
            'total_orders' => PesananMakananHeader::whereIn('id_kost', $kostIds)->count(),
            'menunggu_bayar' => PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', PesananMakananHeader::STATUS_MENUNGGU_BAYAR)->count(),
            'diproses' => PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', PesananMakananHeader::STATUS_DIPROSES)->count(),
            'dikirim' => PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', PesananMakananHeader::STATUS_DIKIRIM)->count(),
            'selesai' => PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', PesananMakananHeader::STATUS_SELESAI)->count(),
            'dibatalkan' => PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', PesananMakananHeader::STATUS_DIBATALKAN)->count(),
            'total_revenue' => PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->whereIn('status_antar', [PesananMakananHeader::STATUS_SELESAI, PesananMakananHeader::STATUS_DIKIRIM])
                ->sum('total_harga'),
        ];

        return response()->json($stats);
    }
}
