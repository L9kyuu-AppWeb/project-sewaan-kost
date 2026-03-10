<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Kamar;
use App\Models\Kost;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesanController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Pesan::with(['kamar.kost', 'latestPayment'])
            ->where('id_penyewa', $user->id_user)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pesan', $request->status);
        }

        $pesanans = $query->paginate(10);

        return view('pesan.index', compact('pesanans'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create($kamarId)
    {
        $kamar = Kamar::with(['kost'])->findOrFail($kamarId);
        
        // Check if room is available
        if ($kamar->status_kamar !== 'tersedia') {
            return redirect()->route('kost-public.show', $kamar->kost->id_kost)
                ->with('error', 'Maaf, kamar ini tidak tersedia.');
        }

        return view('pesan.create', compact('kamar'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kamar' => 'required|exists:kamar,id_kamar',
            'tgl_mulai' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . date('Y-m-d', strtotime('+15 days'))
            ],
            'durasi_bulan' => 'required|integer|min:1|max:24',
            'catatan' => 'nullable|string|max:500',
        ], [
            'tgl_mulai.before_or_equal' => 'Tanggal mulai sewa maksimal 15 hari dari hari ini.',
            'tgl_mulai.after_or_equal' => 'Tanggal mulai sewa tidak boleh di masa lalu.',
        ]);

        $kamar = Kamar::findOrFail($validated['id_kamar']);

        // Check if room is still available
        if ($kamar->status_kamar !== 'tersedia') {
            return back()->withErrors(['id_kamar' => 'Maaf, kamar ini sudah tidak tersedia.'])
                ->withInput();
        }

        // Calculate end date and total price
        $durasiBulan = (int) $validated['durasi_bulan'];
        $tgl_mulai = \Carbon\Carbon::parse($validated['tgl_mulai']);
        $tgl_selesai = $tgl_mulai->copy()->addMonths($durasiBulan);
        $total_harga = Pesan::calculateTotalPrice($kamar, $durasiBulan);

        // Create booking
        $pesan = Pesan::create([
            'id_penyewa' => Auth::id(),
            'id_kamar' => $kamar->id_kamar,
            'tgl_pemesanan' => now(),
            'tgl_mulai' => $validated['tgl_mulai'],
            'durasi_bulan' => $durasiBulan,
            'tgl_selesai' => $tgl_selesai,
            'total_harga' => $total_harga,
            'status_pesan' => Pesan::STATUS_MENUNGGU_PEMBAYARAN,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Update kamar status to 'dipesan'
        $kamar->status_kamar = 'dipesan';
        $kamar->save();

        return redirect()->route('pesan.show', $pesan->id_pesan)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Pesan $pesan)
    {
        // Only allow the penyewa who made the booking
        if ($pesan->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $pesan->load(['kamar.kost', 'payments']);

        return view('pesan.show', compact('pesan'));
    }

    /**
     * Cancel the booking.
     */
    public function cancel(Pesan $pesan)
    {
        // Only allow the penyewa who made the booking
        if ($pesan->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Can only cancel if status is menunggu_pembayaran or proses_verifikasi
        if (!in_array($pesan->status_pesan, [Pesan::STATUS_MENUNGGU_PEMBAYARAN, Pesan::STATUS_PROSES_VERIFIKASI])) {
            return back()->with('error', 'Pemesanan tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $pesan->status_pesan = Pesan::STATUS_DIBATALKAN;
            $pesan->save();

            DB::commit();

            return redirect()->route('pesan.index')
                ->with('success', 'Pemesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pemesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show upload payment proof form.
     */
    public function uploadPayment(Pesan $pesan)
    {
        // Only allow the penyewa who made the booking
        if ($pesan->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Can only upload payment if status is menunggu_pembayaran
        if ($pesan->status_pesan !== Pesan::STATUS_MENUNGGU_PEMBAYARAN) {
            return redirect()->route('pesan.show', $pesan->id_pesan)
                ->with('error', 'Pembayaran sudah diunggah atau sedang diverifikasi.');
        }

        return view('pesan.upload-payment', compact('pesan'));
    }

    /**
     * Upload payment proof.
     */
    public function storePayment(Request $request, Pesan $pesan)
    {
        // Only allow the penyewa who made the booking
        if ($pesan->id_penyewa !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Can only upload payment if status is menunggu_pembayaran
        if ($pesan->status_pesan !== Pesan::STATUS_MENUNGGU_PEMBAYARAN) {
            return redirect()->route('pesan.show', $pesan->id_pesan)
                ->with('error', 'Pembayaran sudah diunggah atau sedang diverifikasi.');
        }

        $validated = $request->validate([
            'jenis_pembayaran' => 'required|in:transfer_bank,ewallet,tunai',
            'nama_bank' => 'required_if:jenis_pembayaran,transfer_bank|string|max:50',
            'nomor_rekening' => 'required_if:jenis_pembayaran,transfer_bank|string|max:50',
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date|before_or_equal:today',
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);

        // Handle photo upload
        $buktiPath = $request->file('bukti_pembayaran')->store('pembayaran', 'public');

        // Create payment record
        $pesan->payments()->create([
            'jenis_pembayaran' => $validated['jenis_pembayaran'],
            'nama_bank' => $validated['nama_bank'] ?? null,
            'nomor_rekening' => $validated['nomor_rekening'] ?? null,
            'bukti_pembayaran' => $buktiPath,
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'status_verifikasi' => Pembayaran::STATUS_PENDING,
        ]);

        // Update pesan status to proses_verifikasi
        $pesan->status_pesan = Pesan::STATUS_PROSES_VERIFIKASI;
        $pesan->save();

        return redirect()->route('pesan.show', $pesan->id_pesan)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi pemilik.');
    }
}
