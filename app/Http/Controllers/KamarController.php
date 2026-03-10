<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    /**
     * Display a listing of the user's rooms.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');
        
        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();
        
        // Get rooms filtered by kost if specified
        $query = Kamar::with('kost')
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });
        
        if ($kostId) {
            $query->where('id_kost', $kostId);
        }
        
        $kamars = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('kamar.index', compact('kamars', 'kosts', 'kostId'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('kamar.create', compact('kosts'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nomor_kamar' => 'required|string|max:10',
            'lantai' => 'nullable|integer|min:0',
            'harga_per_bulan' => 'required|numeric|min:0',
            'status_kamar' => 'required|in:tersedia,dipesan,terisi',
            'ukuran_kamar' => 'nullable|string|max:20',
            'fasilitas_kamar' => 'nullable|string',
            'foto_kamar' => 'nullable|image|max:2048',
        ]);

        // Verify the kost belongs to the authenticated pemilik
        $kost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        // Handle photo upload
        if ($request->hasFile('foto_kamar')) {
            $validated['foto_kamar'] = $request->file('foto_kamar')->store('kamar', 'public');
        }

        $kost->rooms()->create($validated);

        return redirect()->route('kamar.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data kamar berhasil ditambahkan!');
    }

    /**
     * Display the specified room.
     */
    public function show(Kamar $kamar)
    {
        // Only allow owner to view their room
        $kost = Kost::where('id_pemilik', Auth::id())->find($kamar->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        return view('kamar.show', compact('kamar'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Kamar $kamar)
    {
        // Only allow owner to edit their room
        $kost = Kost::where('id_pemilik', Auth::id())->find($kamar->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('kamar.edit', compact('kamar', 'kosts'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Kamar $kamar)
    {
        // Only allow owner to update their room
        $kost = Kost::where('id_pemilik', Auth::id())->find($kamar->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nomor_kamar' => 'required|string|max:10',
            'lantai' => 'nullable|integer|min:0',
            'harga_per_bulan' => 'required|numeric|min:0',
            'status_kamar' => 'required|in:tersedia,dipesan,terisi',
            'ukuran_kamar' => 'nullable|string|max:20',
            'fasilitas_kamar' => 'nullable|string',
            'foto_kamar' => 'nullable|image|max:2048',
        ]);

        // Verify the new kost belongs to the authenticated pemilik
        $newKost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        // Handle photo upload
        if ($request->hasFile('foto_kamar')) {
            // Delete old photo
            if ($kamar->foto_kamar) {
                Storage::disk('public')->delete($kamar->foto_kamar);
            }
            $validated['foto_kamar'] = $request->file('foto_kamar')->store('kamar', 'public');
        }

        $kamar->update($validated);

        return redirect()->route('kamar.index', ['kost_id' => $newKost->id_kost])
            ->with('success', 'Data kamar berhasil diperbarui!');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Kamar $kamar)
    {
        // Only allow owner to delete their room
        $kost = Kost::where('id_pemilik', Auth::id())->find($kamar->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Delete photo if exists
        if ($kamar->foto_kamar) {
            Storage::disk('public')->delete($kamar->foto_kamar);
        }

        $kamar->delete();

        return redirect()->route('kamar.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data kamar berhasil dihapus!');
    }
}
