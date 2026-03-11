<?php

namespace App\Http\Controllers;

use App\Models\LaundryKatalog;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaundryController extends Controller
{
    /**
     * Display a listing of the owner's laundry catalog.
     */
    public function index(Request $request)
    {
        $kostId = $request->query('kost_id');

        // Get kosts owned by the authenticated pemilik
        $kosts = Kost::where('id_pemilik', Auth::id())->get();

        // Get laundry catalog filtered by kost if specified
        $query = LaundryKatalog::with('kost')
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', Auth::id());
            });

        if ($kostId) {
            $query->where('id_kost', $kostId);
        }

        $laundryTypes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('laundry.index', compact('laundryTypes', 'kosts', 'kostId'));
    }

    /**
     * Show the form for creating a new laundry catalog item.
     */
    public function create()
    {
        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('laundry.create', compact('kosts'));
    }

    /**
     * Store a newly created laundry catalog item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nama_layanan' => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        // Verify the kost belongs to the authenticated pemilik
        $kost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        // Set default values
        $validated['is_available'] = $validated['is_available'] ?? true;

        $kost->laundryTypes()->create($validated);

        return redirect()->route('laundry.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data layanan laundry berhasil ditambahkan!');
    }

    /**
     * Display the specified laundry catalog item.
     */
    public function show(LaundryKatalog $laundryType)
    {
        // Only allow owner to view their laundry catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($laundryType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        return view('laundry.show', compact('laundryType'));
    }

    /**
     * Show the form for editing the specified laundry catalog item.
     */
    public function edit(LaundryKatalog $laundryType)
    {
        // Only allow owner to edit their laundry catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($laundryType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $kosts = Kost::where('id_pemilik', Auth::id())
            ->orderBy('nama_kost')
            ->get();

        return view('laundry.edit', compact('laundryType', 'kosts'));
    }

    /**
     * Update the specified laundry catalog item in storage.
     */
    public function update(Request $request, LaundryKatalog $laundryType)
    {
        // Only allow owner to update their laundry catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($laundryType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'id_kost' => 'required|exists:kost,id_kost',
            'nama_layanan' => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        // Verify the new kost belongs to the authenticated pemilik
        $newKost = Kost::where('id_pemilik', Auth::id())->findOrFail($validated['id_kost']);

        $laundryType->update($validated);

        return redirect()->route('laundry.index', ['kost_id' => $newKost->id_kost])
            ->with('success', 'Data layanan laundry berhasil diperbarui!');
    }

    /**
     * Remove the specified laundry catalog item from storage.
     */
    public function destroy(LaundryKatalog $laundryType)
    {
        // Only allow owner to delete their laundry catalog
        $kost = Kost::where('id_pemilik', Auth::id())->find($laundryType->id_kost);
        if (!$kost) {
            abort(403, 'Unauthorized access.');
        }

        $laundryType->delete();

        return redirect()->route('laundry.index', ['kost_id' => $kost->id_kost])
            ->with('success', 'Data layanan laundry berhasil dihapus!');
    }
}
