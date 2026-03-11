<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Pesan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KostPublicController extends Controller
{
    /**
     * Display a listing of available kost for tenants.
     */
    public function index(Request $request)
    {
        $query = Kost::with(['pemilik', 'rooms' => function ($q) {
            $q->where('status_kamar', 'tersedia');
        }]);

        // Search by kost name or address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kost', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        // Filter by city/area (from address)
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where('alamat', 'LIKE', "%{$location}%");
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('harga_per_bulan', '>=', $request->min_price)
                  ->where('status_kamar', 'tersedia');
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('harga_per_bulan', '<=', $request->max_price)
                  ->where('status_kamar', 'tersedia');
            });
        }

        // Filter by facilities
        if ($request->filled('facilities')) {
            $facilities = $request->facilities;
            $query->where('fasilitas_umum', 'LIKE', "%{$facilities}%");
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->whereHas('rooms', function ($q) {
                    $q->where('status_kamar', 'tersedia');
                })->with(['rooms' => function ($q) {
                    $q->where('status_kamar', 'tersedia')->orderBy('harga_per_bulan', 'asc');
                }]);
                break;
            case 'price_high':
                $query->whereHas('rooms', function ($q) {
                    $q->where('status_kamar', 'tersedia');
                })->with(['rooms' => function ($q) {
                    $q->where('status_kamar', 'tersedia')->orderBy('harga_per_bulan', 'desc');
                }]);
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $kosts = $query->paginate(9)->withQueryString();

        return view('kost-public.index', compact('kosts'));
    }

    /**
     * Display available rooms for tenants.
     */
    public function rooms(Request $request)
    {
        $query = Kamar::with(['kost'])
            ->where('status_kamar', 'tersedia')
            ->whereHas('kost', function ($q) {
                $q->where('id_pemilik', '!=', null);
            });

        // Search by room number or kost name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_kamar', 'LIKE', "%{$search}%")
                  ->orWhereHas('kost', function ($kq) use ($search) {
                      $kq->where('nama_kost', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('kost', function ($q) use ($location) {
                $q->where('alamat', 'LIKE', "%{$location}%");
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('harga_per_bulan', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('harga_per_bulan', '<=', $request->max_price);
        }

        // Filter by floor
        if ($request->filled('floor')) {
            $query->where('lantai', $request->floor);
        }

        // Filter by facilities
        if ($request->filled('facilities')) {
            $facilities = $request->facilities;
            $query->where('fasilitas_kamar', 'LIKE', "%{$facilities}%");
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('harga_per_bulan', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga_per_bulan', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $kamars = $query->paginate(12)->withQueryString();

        return view('kost-public.rooms', compact('kamars'));
    }

    /**
     * Display the specified kost with available rooms.
     */
    public function show(Kost $kost)
    {
        $kost->load(['rooms' => function ($q) {
            $q->where('status_kamar', 'tersedia')->orderBy('harga_per_bulan', 'asc');
        }]);

        return view('kost-public.show', compact('kost'));
    }
}
