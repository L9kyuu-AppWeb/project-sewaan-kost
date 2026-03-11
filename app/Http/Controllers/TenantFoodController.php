<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Pesan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantFoodController extends Controller
{
    /**
     * Display available food menus for the tenant's kost.
     */
    public function index()
    {
        $tenant = Auth::user();
        
        // Get the tenant's active booking
        $activePesanan = Pesan::where('id_penyewa', $tenant->id_user)
            ->whereIn('status_pesan', [Pesan::STATUS_AKTIF, Pesan::STATUS_PROSES_VERIFIKASI])
            ->with('kamar')
            ->first();

        if (!$activePesanan || !$activePesanan->kamar) {
            // Tenant doesn't have an active booking
            return view('tenant.food.index', [
                'makanans' => collect([]),
                'kost' => null,
                'message' => 'Anda belum memiliki pemesanan kost aktif. Silakan pesan kost terlebih dahulu untuk melihat menu makanan.'
            ]);
        }

        // Get the kost from the room
        $kost = $activePesanan->kamar->kost;

        // Get available food menus for this kost
        $makanans = Makanan::where('id_kost', $kost->id_kost)
            ->where('is_available', true)
            ->where('stok', '>', 0)
            ->orderBy('nama_makanan')
            ->get();

        return view('tenant.food.index', compact('makanans', 'kost'));
    }

    /**
     * Display all food menus (including unavailable) for tenant's kost.
     */
    public function allMenus()
    {
        $tenant = Auth::user();
        
        // Get the tenant's active booking
        $activePesanan = Pesan::where('id_penyewa', $tenant->id_user)
            ->whereIn('status_pesan', [Pesan::STATUS_AKTIF, Pesan::STATUS_PROSES_VERIFIKASI])
            ->with('kamar')
            ->first();

        if (!$activePesanan || !$activePesanan->kamar) {
            return view('tenant.food.all-menus', [
                'makanans' => collect([]),
                'kost' => null,
                'message' => 'Anda belum memiliki pemesanan kost aktif.'
            ]);
        }

        // Get the kost from the room
        $kost = $activePesanan->kamar->kost;

        // Get all food menus for this kost
        $makanans = Makanan::where('id_kost', $kost->id_kost)
            ->orderBy('is_available', 'desc')
            ->orderBy('nama_makanan')
            ->get();

        return view('tenant.food.all-menus', compact('makanans', 'kost'));
    }
}
