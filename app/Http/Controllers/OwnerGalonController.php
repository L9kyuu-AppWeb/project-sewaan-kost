<?php

namespace App\Http\Controllers;

use App\Models\PesananGalon;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerGalonController extends Controller
{
    /**
     * Display all galon orders for owner's kosts.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');
        $status = $request->query('status');

        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();

        // Get orders filtered by kost and status
        $query = PesananGalon::with(['penyewa', 'galonType', 'kost'])
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });

        if ($kostId) {
            $query->where('id_kost', $kostId);
        }

        if ($status) {
            $query->where('status_galon', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('owner.galon.index', compact('orders', 'kosts', 'kostId', 'status'));
    }

    /**
     * Display the specified galon order.
     */
    public function show(PesananGalon $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['penyewa', 'galonType', 'pembayaran']);

        return view('owner.galon.show', compact('order'));
    }

    /**
     * Update the order status to 'diproses'.
     */
    public function process(PesananGalon $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow processing if status is menunggu_bayar
        if ($order->status_galon !== PesananGalon::STATUS_MENUNGGU_BAYAR) {
            return redirect()->back()
                ->with('error', 'Pesanan sudah diproses atau memiliki status lain.');
        }

        $order->update(['status_galon' => PesananGalon::STATUS_DIPROSES]);

        return redirect()->route('owner.galon.index')
            ->with('success', 'Pesanan berhasil ditandai sebagai diproses.');
    }

    /**
     * Update the order status to 'diambil' and upload photo of filled gallon.
     */
    public function deliver(Request $request, PesananGalon $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow delivery if status is diproses
        if ($order->status_galon !== PesananGalon::STATUS_DIPROSES) {
            return redirect()->back()
                ->with('error', 'Pesanan harus diproses sebelum diantar.');
        }

        $validated = $request->validate([
            'foto_terisi' => 'required|image|max:2048',
        ], [
            'foto_terisi.required' => 'Foto galon terisi wajib diunggah.',
            'foto_terisi.image' => 'File harus berupa gambar.',
            'foto_terisi.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto_terisi')) {
            // Delete old photo if exists
            if ($order->foto_terisi) {
                Storage::disk('public')->delete($order->foto_terisi);
            }
            $fotoPath = $request->file('foto_terisi')->store('galon/terisi', 'public');
        }

        $order->update([
            'foto_terisi' => $fotoPath,
            'status_galon' => PesananGalon::STATUS_DIAMBIL,
        ]);

        return redirect()->route('owner.galon.index')
            ->with('success', 'Pesanan berhasil ditandai sebagai diantar. Foto bukti telah diunggah.');
    }

    /**
     * Cancel the order (for owner).
     */
    public function cancel(PesananGalon $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow cancellation if status is menunggu_bayar or diproses
        if (!in_array($order->status_galon, [PesananGalon::STATUS_MENUNGGU_BAYAR, PesananGalon::STATUS_DIPROSES])) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $order->update(['status_galon' => PesananGalon::STATUS_DIBATALKAN]);

        return redirect()->route('owner.galon.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Get order statistics for dashboard.
     */
    public function stats()
    {
        $kostIds = Kost::where('id_pemilik', Auth::id())->pluck('id_kost');

        $stats = [
            'total_orders' => PesananGalon::whereIn('id_kost', $kostIds)->count(),
            'menunggu_bayar' => PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', PesananGalon::STATUS_MENUNGGU_BAYAR)->count(),
            'diproses' => PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', PesananGalon::STATUS_DIPROSES)->count(),
            'diambil' => PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', PesananGalon::STATUS_DIAMBIL)->count(),
            'selesai' => PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', PesananGalon::STATUS_SELESAI)->count(),
            'dibatalkan' => PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', PesananGalon::STATUS_DIBATALKAN)->count(),
            'total_revenue' => PesananGalon::whereIn('id_kost', $kostIds)
                ->whereIn('status_galon', [PesananGalon::STATUS_SELESAI, PesananGalon::STATUS_DIAMBIL])
                ->sum('total_bayar'),
        ];

        return response()->json($stats);
    }
}
