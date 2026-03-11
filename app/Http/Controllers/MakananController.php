<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MakananController extends Controller
{
    /**
     * Display a listing of the user's food menus.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');

        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();

        // Get food menus filtered by kost if specified
        $query = Makanan::with('kost')
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });

        if ($kostId) {
            $query->where('id_kost', $kostId);
        }

        $makanans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('makanan.index', compact('makanans', 'kosts', 'kostId'));
    }

    /**
     * Show the form for creating a new food menu.
     */
    public function create()
    {
        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('makanan.create', compact('kosts'));
    }

    /**
     * Store a newly created food menu in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nama_makanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
            'foto_makanan' => 'nullable|image|max:2048',
        ]);

        // Verify the kost belongs to the authenticated pemilik
        $kost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        // Handle photo upload
        if ($request->hasFile('foto_makanan')) {
            $validated['foto_makanan'] = $request->file('foto_makanan')->store('makanan', 'public');
        }

        // Set default values
        $validated['stok'] = $validated['stok'] ?? 0;
        $validated['is_available'] = $validated['is_available'] ?? true;

        $kost->foods()->create($validated);

        return redirect()->route('makanan.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data makanan berhasil ditambahkan!');
    }

    /**
     * Display the specified food menu.
     */
    public function show(Makanan $makanan)
    {
        // Only allow owner to view their food menu
        $kost = Kost::where('id_pemilik', Auth::id())->find($makanan->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        return view('makanan.show', compact('makanan'));
    }

    /**
     * Show the form for editing the specified food menu.
     */
    public function edit(Makanan $makanan)
    {
        // Only allow owner to edit their food menu
        $kost = Kost::where('id_pemilik', Auth::id())->find($makanan->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('makanan.edit', compact('makanan', 'kosts'));
    }

    /**
     * Update the specified food menu in storage.
     */
    public function update(Request $request, Makanan $makanan)
    {
        // Only allow owner to update their food menu
        $kost = Kost::where('id_pemilik', Auth::id())->find($makanan->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nama_makanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
            'foto_makanan' => 'nullable|image|max:2048',
        ]);

        // Verify the new kost belongs to the authenticated pemilik
        $newKost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        // Handle photo upload
        if ($request->hasFile('foto_makanan')) {
            // Delete old photo
            if ($makanan->foto_makanan) {
                Storage::disk('public')->delete($makanan->foto_makanan);
            }
            $validated['foto_makanan'] = $request->file('foto_makanan')->store('makanan', 'public');
        }

        $makanan->update($validated);

        return redirect()->route('makanan.index', ['kost_id' => $newKost->id_kost])
            ->with('success', 'Data makanan berhasil diperbarui!');
    }

    /**
     * Remove the specified food menu from storage.
     */
    public function destroy(Makanan $makanan)
    {
        // Only allow owner to delete their food menu
        $kost = Kost::where('id_pemilik', Auth::id())->find($makanan->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        // Delete photo if exists
        if ($makanan->foto_makanan) {
            Storage::disk('public')->delete($makanan->foto_makanan);
        }

        $makanan->delete();

        return redirect()->route('makanan.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data makanan berhasil dihapus!');
    }
}
