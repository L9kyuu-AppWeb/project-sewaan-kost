<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KostController extends Controller
{
    /**
     * Display a listing of the user's kosts.
     */
    public function index()
    {
        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kost.index', compact('kosts'));
    }

    /**
     * Show the form for creating a new kost.
     */
    public function create()
    {
        return view('kost.create');
    }

    /**
     * Store a newly created kost in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kost' => 'required|string|max:100',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'fasilitas_umum' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'foto_kost' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle photo upload
        if ($request->hasFile('foto_kost')) {
            $validated['foto_kost'] = $request->file('foto_kost')->store('kost', 'public');
        }

        $validated['id_pemilik'] = Auth::id();

        Kost::create($validated);

        return redirect()->route('kost.index')
            ->with('success', 'Data kost berhasil ditambahkan!');
    }

    /**
     * Display the specified kost.
     */
    public function show(Kost $kost)
    {
        // Only allow owner to view their kost
        if ($kost->id_pemilik !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('kost.show', compact('kost'));
    }

    /**
     * Show the form for editing the specified kost.
     */
    public function edit(Kost $kost)
    {
        // Only allow owner to edit their kost
        if ($kost->id_pemilik !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('kost.edit', compact('kost'));
    }

    /**
     * Update the specified kost in storage.
     */
    public function update(Request $request, Kost $kost)
    {
        // Only allow owner to update their kost
        if ($kost->id_pemilik !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'nama_kost' => 'required|string|max:100',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'fasilitas_umum' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'foto_kost' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle photo upload
        if ($request->hasFile('foto_kost')) {
            // Delete old photo
            if ($kost->foto_kost) {
                \Storage::disk('public')->delete($kost->foto_kost);
            }
            $validated['foto_kost'] = $request->file('foto_kost')->store('kost', 'public');
        }

        $kost->update($validated);

        return redirect()->route('kost.index')
            ->with('success', 'Data kost berhasil diperbarui!');
    }

    /**
     * Remove the specified kost from storage.
     */
    public function destroy(Kost $kost)
    {
        // Only allow owner to delete their kost
        if ($kost->id_pemilik !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Delete photo if exists
        if ($kost->foto_kost) {
            \Storage::disk('public')->delete($kost->foto_kost);
        }

        $kost->delete();

        return redirect()->route('kost.index')
            ->with('success', 'Data kost berhasil dihapus!');
    }
}
