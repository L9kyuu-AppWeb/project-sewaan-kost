<?php

namespace App\Http\Controllers;

use App\Models\GalonKatalog;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalonController extends Controller
{
    /**
     * Display a listing of the owner's galon catalog.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');

        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();

        // Get galon catalog filtered by kost if specified
        $query = GalonKatalog::with('kost')
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });

        if ($kostId) {
            $query->where('id_kost', $kostId);
        }

        $galonTypes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('galon.index', compact('galonTypes', 'kosts', 'kostId'));
    }

    /**
     * Show the form for creating a new galon catalog item.
     */
    public function create()
    {
        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('galon.create', compact('kosts'));
    }

    /**
     * Store a newly created galon catalog item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nama_air' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        // Verify the kost belongs to the authenticated pemilik
        $kost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        // Set default values
        $validated['is_available'] = $validated['is_available'] ?? true;

        $kost->galonTypes()->create($validated);

        return redirect()->route('galon.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data jenis air galon berhasil ditambahkan!');
    }

    /**
     * Display the specified galon catalog item.
     */
    public function show(GalonKatalog $galonType)
    {
        // Only allow owner to view their galon catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($galonType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        return view('galon.show', compact('galonType'));
    }

    /**
     * Show the form for editing the specified galon catalog item.
     */
    public function edit(GalonKatalog $galonType)
    {
        // Only allow owner to edit their galon catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($galonType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('galon.edit', compact('galonType', 'kosts'));
    }

    /**
     * Update the specified galon catalog item in storage.
     */
    public function update(Request $request, GalonKatalog $galonType)
    {
        // Only allow owner to update their galon catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($galonType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nama_air' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        // Verify the new kost belongs to the authenticated pemilik
        $newKost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        $galonType->update($validated);

        return redirect()->route('galon.index', ['kost_id' => $newKost->id_kost])
            ->with('success', 'Data jenis air galon berhasil diperbarui!');
    }

    /**
     * Remove the specified galon catalog item from storage.
     */
    public function destroy(GalonKatalog $galonType)
    {
        // Only allow owner to delete their galon catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($galonType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $galonType->delete();

        return redirect()->route('galon.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data jenis air galon berhasil dihapus!');
    }
}
