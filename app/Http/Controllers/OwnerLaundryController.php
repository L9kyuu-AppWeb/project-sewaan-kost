<?php

namespace App\Http\Controllers;

use App\Models\PesananLaundry;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerLaundryController extends Controller
{
    /**
     * Display all laundry orders for owner's kosts.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');
        $status = $request->query('status');

        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();

        // Get orders filtered by kost and status
        $query = PesananLaundry::with(['penyewa', 'laundryType', 'kost'])
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });

        if ($kostId) {
            $query->where('id_kost', $kostId);
        }

        if ($status) {
            $query->where('status_laundry', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('owner.laundry.index', compact('orders', 'kosts', 'kostId', 'status'));
    }

    /**
     * Display the specified laundry order.
     */
    public function show(PesananLaundry $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['penyewa', 'laundryType', 'pembayaran']);

        return view('owner.laundry.show', compact('order'));
    }

    /**
     * Update weight and price (owner weighs the clothes).
     */
    public function weigh(Request $request, PesananLaundry $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow weighing if status is menunggu_jemput
        if ($order->status_laundry !== PesananLaundry::STATUS_MENUNGGU_JEMPUT) {
            return redirect()->back()
                ->with('error', 'Pesanan sudah diproses atau tidak dapat ditimbang.');
        }

        $validated = $request->validate([
            'berat_kg' => 'required|numeric|min:0.1|max:100',
        ]);

        $laundryType = $order->laundryType;
        $totalHarga = $laundryType->harga_per_kg * $validated['berat_kg'];

        $order->update([
            'berat_kg' => $validated['berat_kg'],
            'total_harga' => $totalHarga,
            'status_laundry' => PesananLaundry::STATUS_MENUNGGU_BAYAR,
        ]);

        return redirect()->route('owner.laundry.show', $order->id_order_laundry)
            ->with('success', 'Berat dan harga berhasil diupdate. Penyewa akan melakukan pembayaran.');
    }

    /**
     * Set estimation date for completion (required after payment).
     */
    public function setEstimation(Request $request, PesananLaundry $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Check if estimation can be set
        if (!$order->canSetEstimationDate()) {
            return redirect()->back()
                ->with('error', 'Estimasi selesai hanya bisa diisi setelah pembayaran lunas (status: sedang_dicuci).');
        }

        $validated = $request->validate([
            'tgl_selesai_estimasi' => 'required|date|after_or_equal:today',
        ]);

        $order->update([
            'tgl_selesai_estimasi' => $validated['tgl_selesai_estimasi'],
        ]);

        return redirect()->route('owner.laundry.show', $order->id_order_laundry)
            ->with('success', 'Tanggal estimasi selesai berhasil diisi.');
    }

    /**
     * Upload finished photo and mark as ready for delivery.
     */
    public function uploadFinished(Request $request, PesananLaundry $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow upload if estimation date is set
        if (!$order->canUploadFinishedPhoto()) {
            return redirect()->back()
                ->with('error', 'Tanggal estimasi harus diisi sebelum mengunggah foto selesai.');
        }

        $validated = $request->validate([
            'foto_selesai' => 'required|image|max:2048',
        ]);

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto_selesai')) {
            // Delete old photo if exists
            if ($order->foto_selesai) {
                Storage::disk('public')->delete($order->foto_selesai);
            }
            $fotoPath = $request->file('foto_selesai')->store('laundry/selesai', 'public');
        }

        $order->update([
            'foto_selesai' => $fotoPath,
            'status_laundry' => PesananLaundry::STATUS_Siap_ANTAR,
            // tgl_selesai_aktual will be auto-set by model event
        ]);

        return redirect()->route('owner.laundry.show', $order->id_order_laundry)
            ->with('success', 'Foto selesai berhasil diunggah. Pesanan siap diantar.');
    }

    /**
     * Cancel the order (for owner).
     */
    public function cancel(PesananLaundry $order)
    {
        // Verify the order belongs to owner's kost
        $kost = Kost::where('id_pemilik', Auth::id())->find($order->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow cancellation if status is menunggu_jemput or menunggu_bayar
        if (!in_array($order->status_laundry, [PesananLaundry::STATUS_MENUNGGU_JEMPUT, PesananLaundry::STATUS_MENUNGGU_BAYAR])) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $order->update(['status_laundry' => PesananLaundry::STATUS_DIBATALKAN]);

        return redirect()->route('owner.laundry.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Get order statistics for dashboard.
     */
    public function stats()
    {
        $kostIds = Kost::where('id_pemilik', Auth::id())->pluck('id_kost');

        $stats = [
            'total_orders' => PesananLaundry::whereIn('id_kost', $kostIds)->count(),
            'menunggu_jemput' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', PesananLaundry::STATUS_MENUNGGU_JEMPUT)->count(),
            'menunggu_bayar' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', PesananLaundry::STATUS_MENUNGGU_BAYAR)->count(),
            'sedang_dicuci' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', PesananLaundry::STATUS_SEDANG_DICUCI)->count(),
            'siap_antar' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', PesananLaundry::STATUS_Siap_ANTAR)->count(),
            'selesai' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', PesananLaundry::STATUS_SELESAI)->count(),
            'late_orders' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', PesananLaundry::STATUS_SELESAI)
                ->whereColumn('tgl_selesai_aktual', '>', 'tgl_selesai_estimasi')
                ->count(),
            'total_revenue' => PesananLaundry::whereIn('id_kost', $kostIds)
                ->whereIn('status_laundry', [PesananLaundry::STATUS_SELESAI, PesananLaundry::STATUS_Siap_ANTAR])
                ->sum('total_harga'),
        ];

        return response()->json($stats);
    }
}
