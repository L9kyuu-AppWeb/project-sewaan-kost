<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\KostSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KostSettingController extends Controller
{
    /**
     * Display settings form for a specific kost.
     */
    public function edit($kostId)
    {
        // Verify the kost belongs to the authenticated pemilik
        $kost = Kost::where('id_pemilik', Auth::id())->findOrFail($kostId);
        
        // Get or create settings
        $setting = KostSetting::getOrCreate($kost->id_kost);
        
        return view('kost.settings.edit', compact('kost', 'setting'));
    }

    /**
     * Update settings for a specific kost.
     */
    public function update(Request $request, $kostId)
    {
        // Verify the kost belongs to the authenticated pemilik
        $kost = Kost::where('id_pemilik', Auth::id())->findOrFail($kostId);
        
        $validated = $request->validate([
            'enable_makanan' => 'nullable|boolean',
            'enable_galon' => 'nullable|boolean',
            'enable_laundry' => 'nullable|boolean',
        ]);

        // Get or create settings
        $setting = KostSetting::getOrCreate($kost->id_kost);
        
        // Update settings (unchecked checkboxes won't be sent, so default to false)
        $setting->update([
            'enable_makanan' => $validated['enable_makanan'] ?? false,
            'enable_galon' => $validated['enable_galon'] ?? false,
            'enable_laundry' => $validated['enable_laundry'] ?? false,
        ]);

        return redirect()->route('kost.settings.edit', $kost->id_kost)
            ->with('success', 'Pengaturan fitur berhasil diperbarui!');
    }
}
