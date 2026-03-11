<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Pesan;
use App\Models\Pembayaran;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesanOwnerController extends Controller
{
    /**
     * Display a listing of bookings for owner's rooms.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all bookings for rooms owned by this pemilik
        $query = Pesan::with(['penyewa', 'kamar.kost', 'latestPayment'])
            ->whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pesan', $request->status);
        }

        // Filter by kost
        if ($request->filled('kost_id')) {
            $query->whereHas('kamar', function ($q) use ($request) {
                $q->where('id_kost', $request->kost_id);
            });
        }

        $pesanans = $query->paginate(15);

        // Get owner's kosts for filter dropdown
        $kosts = Kost::where('id_pemilik', $user->id_user)->get();

        // Get statistics
        $stats = [
            'total' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->count(),
            'menunggu_pembayaran' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_pesan', Pesan::STATUS_MENUNGGU_PEMBAYARAN)->count(),
            'aktif' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_pesan', Pesan::STATUS_AKTIF)->count(),
            'pending_payments' => Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('transaction_status', 'pending')->count(),
        ];

        return view('pesan.owner.index', compact('pesanans', 'stats'));
    }

    /**
     * Display the specified booking for owner.
     */
    public function show(Pesan $pesan)
    {
        $user = Auth::user();

        // Verify owner has access to this booking
        $kost = Kost::where('id_pemilik', $user->id_user)
            ->whereHas('rooms', function ($q) use ($pesan) {
                $q->where('id_kamar', $pesan->id_kamar);
            })
            ->first();

        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $pesan->load(['penyewa', 'kamar.kost', 'payments.verifiedBy']);

        return view('pesan.owner.show', compact('pesan', 'kost'));
    }

    /**
     * Extend booking duration.
     */
    public function extendDuration(Request $request, Pesan $pesan)
    {
        $user = Auth::user();

        // Verify owner has access to this booking
        $kost = Kost::where('id_pemilik', $user->id_user)
            ->whereHas('rooms', function ($q) use ($pesan) {
                $q->where('id_kamar', $pesan->id_kamar);
            })
            ->first();

        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'durasi_tambahan' => 'required|integer|min:1|max:24',
        ]);

        DB::beginTransaction();
        try {
            $newDurasi = (int) $pesan->durasi_bulan + (int) $validated['durasi_tambahan'];
            $newTglSelesai = \Carbon\Carbon::parse($pesan->tgl_mulai)
                ->addMonths((int) $newDurasi);
            $newTotalHarga = Pesan::calculateTotalPrice($pesan->kamar, (int) $newDurasi);

            $pesan->durasi_bulan = (int) $newDurasi;
            $pesan->tgl_selesai = $newTglSelesai;
            $pesan->total_harga = $newTotalHarga;
            $pesan->save();

            DB::commit();

            return redirect()->route('pesan.owner.show', $pesan->id_pesan)
                ->with('success', 'Durasi sewa berhasil diperpanjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperpanjang durasi sewa.');
        }
    }

    /**
     * Mark booking as completed (when lease expires).
     */
    public function markCompleted(Pesan $pesan)
    {
        $user = Auth::user();

        // Verify owner has access to this booking
        $kost = Kost::where('id_pemilik', $user->id_user)
            ->whereHas('rooms', function ($q) use ($pesan) {
                $q->where('id_kamar', $pesan->id_kamar);
            })
            ->first();

        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Can only complete if status is aktif and end date has passed
        if ($pesan->status_pesan !== Pesan::STATUS_AKTIF) {
            return back()->with('error', 'Pemesanan tidak dapat diselesaikan.');
        }

        DB::beginTransaction();
        try {
            $pesan->status_pesan = Pesan::STATUS_SELESAI;
            $pesan->save();

            // Update kamar status to tersedia
            $pesan->kamar->status_kamar = 'tersedia';
            $pesan->kamar->save();

            DB::commit();

            return redirect()->route('pesan.owner.index')
                ->with('success', 'Pemesanan berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan pemesanan.');
        }
    }

    /**
     * Get statistics for owner dashboard.
     */
    public function stats()
    {
        $user = Auth::user();

        $stats = [
            'total' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->count(),

            'menunggu_pembayaran' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_pesan', Pesan::STATUS_MENUNGGU_PEMBAYARAN)->count(),

            'proses_verifikasi' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_pesan', Pesan::STATUS_PROSES_VERIFIKASI)->count(),

            'aktif' => Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_pesan', Pesan::STATUS_AKTIF)->count(),

            'pending_payments' => Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('transaction_status', 'pending')->count(),
        ];

        return response()->json($stats);
    }
}
