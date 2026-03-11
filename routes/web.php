<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalonController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\KostSettingController;
use App\Http\Controllers\KostPublicController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OwnerGalonController;
use App\Http\Controllers\OwnerLaundryController;
use App\Http\Controllers\OwnerOrderController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PesanOwnerController;
use App\Http\Controllers\TenantGalonController;
use App\Http\Controllers\TenantLaundryController;
use App\Http\Controllers\TenantOrderController;
use App\Models\Pesan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Public kost listing routes (accessible to everyone)
Route::prefix('kost-public')->name('kost-public.')->group(function () {
    Route::get('/', [KostPublicController::class, 'index'])->name('index');
    Route::get('/rooms', [KostPublicController::class, 'rooms'])->name('rooms');
    Route::get('/{kost}', [KostPublicController::class, 'show'])->name('show');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard routes based on role
    Route::get('/dashboard/pemilik', function () {
        if (auth()->user()->role !== 'pemilik') {
            return redirect('/dashboard/penyewa');
        }

        $user = auth()->user();
        
        // Get all kost IDs owned by the user
        $kostIds = \App\Models\Kost::where('id_pemilik', $user->id_user)->pluck('id_kost');
        
        $stats = [
            'total_kost' => \App\Models\Kost::where('id_pemilik', $user->id_user)->count(),
            'total_kamar' => \App\Models\Kamar::whereIn('id_kost', $kostIds)->count(),
            'kamar_terisi' => \App\Models\Kamar::whereIn('id_kost', $kostIds)->where('status_kamar', 'terisi')->count(),
            'penyewa_aktif' => \App\Models\Pesan::whereHas('kamar.kost', function ($q) use ($kostIds) {
                $q->whereIn('id_kost', $kostIds);
            })->where('status_pesan', 'aktif')->count(),
            'pending_payments' => \App\Models\Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($kostIds) {
                $q->whereIn('id_kost', $kostIds);
            })->where('transaction_status', 'pending')->count(),

            // Revenue statistics - KAMAR payments only
            'pendapatan_kamar_bulan_ini' => \App\Models\Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($kostIds) {
                    $q->whereIn('id_kost', $kostIds);
                })
                ->where('tipe_pembayaran', 'kamar')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->whereMonth('settlement_time', now()->month)
                ->sum('jumlah_bayar'),

            'pendapatan_kamar_tahun_ini' => \App\Models\Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($kostIds) {
                    $q->whereIn('id_kost', $kostIds);
                })
                ->where('tipe_pembayaran', 'kamar')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->sum('jumlah_bayar'),

            'pendapatan_kamar_total' => \App\Models\Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($kostIds) {
                    $q->whereIn('id_kost', $kostIds);
                })
                ->where('tipe_pembayaran', 'kamar')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->sum('jumlah_bayar'),

            // Revenue statistics - GALON payments
            'pendapatan_galon_bulan_ini' => \App\Models\Pembayaran::where('tipe_pembayaran', 'galon')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->whereMonth('settlement_time', now()->month)
                ->sum('jumlah_bayar'),

            'pendapatan_galon_tahun_ini' => \App\Models\Pembayaran::where('tipe_pembayaran', 'galon')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->sum('jumlah_bayar'),

            'pendapatan_galon_total' => \App\Models\Pembayaran::where('tipe_pembayaran', 'galon')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->sum('jumlah_bayar'),

            // Revenue statistics - LAUNDRY payments
            'pendapatan_laundry_bulan_ini' => \App\Models\Pembayaran::where('tipe_pembayaran', 'laundry')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->whereMonth('settlement_time', now()->month)
                ->sum('jumlah_bayar'),

            'pendapatan_laundry_tahun_ini' => \App\Models\Pembayaran::where('tipe_pembayaran', 'laundry')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->sum('jumlah_bayar'),

            'pendapatan_laundry_total' => \App\Models\Pembayaran::where('tipe_pembayaran', 'laundry')
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->sum('jumlah_bayar'),

        // Order statistics - MAKANAN
            'total_pesanan_makanan' => \App\Models\PesananMakananHeader::whereIn('id_kost', $kostIds)->count(),
            'pesanan_makanan_pending' => \App\Models\PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', 'menunggu_bayar')->count(),
            'pesanan_makanan_proses' => \App\Models\PesananMakananHeader::whereIn('id_kost', $kostIds)
                ->where('status_antar', 'diproses')->count(),

            // Order statistics - GALON
            'total_pesanan_galon' => \App\Models\PesananGalon::whereIn('id_kost', $kostIds)->count(),
            'pesanan_galon_pending' => \App\Models\PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', 'menunggu_bayar')->count(),
            'pesanan_galon_proses' => \App\Models\PesananGalon::whereIn('id_kost', $kostIds)
                ->where('status_galon', 'diproses')->count(),

            // Order statistics - LAUNDRY
            'total_pesanan_laundry' => \App\Models\PesananLaundry::whereIn('id_kost', $kostIds)->count(),
            'pesanan_laundry_pending' => \App\Models\PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', 'menunggu_bayar')->count(),
            'pesanan_laundry_proses' => \App\Models\PesananLaundry::whereIn('id_kost', $kostIds)
                ->where('status_laundry', 'sedang_dicuci')->count(),

            // Feature settings
            'features_enabled' => [
                'makanan' => \App\Models\Kost::whereIn('id_kost', $kostIds)
                    ->whereHas('setting', function($q) { $q->where('enable_makanan', true); })->count() > 0,
                'galon' => \App\Models\Kost::whereIn('id_kost', $kostIds)
                    ->whereHas('setting', function($q) { $q->where('enable_galon', true); })->count() > 0,
                'laundry' => \App\Models\Kost::whereIn('id_kost', $kostIds)
                    ->whereHas('setting', function($q) { $q->where('enable_laundry', true); })->count() > 0,
            ],

            // TOTAL Revenue (all payment types)
            'pendapatan_bulan_ini' => \App\Models\Pembayaran::whereIn('tipe_pembayaran', ['kamar', 'makanan', 'galon', 'laundry'])
                ->where(function ($q) use ($kostIds) {
                    $q->whereHas('pesan.kamar.kost', function ($q2) use ($kostIds) {
                            $q2->whereIn('id_kost', $kostIds);
                        })
                        ->orWhereHas('pesananMakanan.kost', function ($q2) use ($kostIds) {
                            $q2->whereIn('id_kost', $kostIds);
                        });
                })
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->whereMonth('settlement_time', now()->month)
                ->sum('jumlah_bayar'),

            'pendapatan_tahun_ini' => \App\Models\Pembayaran::whereIn('tipe_pembayaran', ['kamar', 'makanan', 'galon', 'laundry'])
                ->where(function ($q) use ($kostIds) {
                    $q->whereHas('pesan.kamar.kost', function ($q2) use ($kostIds) {
                            $q2->whereIn('id_kost', $kostIds);
                        })
                        ->orWhereHas('pesananMakanan.kost', function ($q2) use ($kostIds) {
                            $q2->whereIn('id_kost', $kostIds);
                        });
                })
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->whereYear('settlement_time', now()->year)
                ->sum('jumlah_bayar'),

            'pendapatan_total' => \App\Models\Pembayaran::whereIn('tipe_pembayaran', ['kamar', 'makanan', 'galon', 'laundry'])
                ->where(function ($q) use ($kostIds) {
                    $q->whereHas('pesan.kamar.kost', function ($q2) use ($kostIds) {
                            $q2->whereIn('id_kost', $kostIds);
                        })
                        ->orWhereHas('pesananMakanan.kost', function ($q2) use ($kostIds) {
                            $q2->whereIn('id_kost', $kostIds);
                        });
                })
                ->whereIn('transaction_status', ['settlement', 'capture'])
                ->sum('jumlah_bayar'),
        ];

        return view('dashboard.pemilik', compact('stats'));
    })->name('dashboard.pemilik');

    Route::get('/dashboard/penyewa', function () {
        if (auth()->user()->role !== 'penyewa') {
            return redirect('/dashboard/pemilik');
        }

        $user = auth()->user();

        // Get ALL active bookings with room and kost info
        $pesanAktifList = Pesan::with(['kamar.kost', 'payments'])
            ->where('id_penyewa', $user->id_user)
            ->where('status_pesan', 'aktif')
            ->latest('id_pesan')
            ->get();

        // Get latest pending payment (Midtrans transaction_status = pending)
        $pembayaranPending = Pembayaran::with('pesan')
            ->whereHas('pesan', function ($q) use ($user) {
                $q->where('id_penyewa', $user->id_user)
                  ->where('status_pesan', 'menunggu_pembayaran');
            })
            ->where('transaction_status', 'pending')
            ->latest('id_pembayaran')
            ->first();

        $stats = [
            'total_pemesanan' => Pesan::where('id_penyewa', $user->id_user)->count(),
            'pemesanan_aktif' => Pesan::where('id_penyewa', $user->id_user)->where('status_pesan', 'aktif')->count(),
            'pemesanan_pending' => Pesan::where('id_penyewa', $user->id_user)->whereIn('status_pesan', ['menunggu_pembayaran', 'proses_verifikasi'])->count(),
            'pesan_aktif_list' => $pesanAktifList,
            'kamar_saat_ini' => $pesanAktifList->first()?->kamar, // For backward compatibility
            'kost_saat_ini' => $pesanAktifList->first()?->kamar?->kost, // For backward compatibility
            'pembayaran_pending' => $pembayaranPending,
            'total_pembayaran_pending' => $pembayaranPending?->jumlah_bayar ?? 0,

            // Food orders statistics
            'total_pesanan_makanan' => \App\Models\PesananMakananHeader::where('id_penyewa', $user->id_user)->count(),
            'pesanan_makanan_pending' => \App\Models\PesananMakananHeader::where('id_penyewa', $user->id_user)
                ->where('status_antar', 'menunggu_bayar')->count(),
            'pesanan_makanan_proses' => \App\Models\PesananMakananHeader::where('id_penyewa', $user->id_user)
                ->whereIn('status_antar', ['diproses', 'dikirim'])->count(),

            // Galon orders statistics
            'total_pesanan_galon' => \App\Models\PesananGalon::where('id_penyewa', $user->id_user)->count(),
            'pesanan_galon_pending' => \App\Models\PesananGalon::where('id_penyewa', $user->id_user)
                ->where('status_galon', 'menunggu_bayar')->count(),
            'pesanan_galon_proses' => \App\Models\PesananGalon::where('id_penyewa', $user->id_user)
                ->whereIn('status_galon', ['diproses', 'diambil'])->count(),

            // Laundry orders statistics
            'total_pesanan_laundry' => \App\Models\PesananLaundry::where('id_penyewa', $user->id_user)->count(),
            'pesanan_laundry_pending' => \App\Models\PesananLaundry::where('id_penyewa', $user->id_user)
                ->where('status_laundry', 'menunggu_bayar')->count(),
            'pesanan_laundry_proses' => \App\Models\PesananLaundry::where('id_penyewa', $user->id_user)
                ->whereIn('status_laundry', ['sedang_dicuci', 'siap_antar'])->count(),
        ];

        return view('dashboard.penyewa', compact('stats'));
    })->name('dashboard.penyewa');

    // Kost management routes (for pemilik only)
    Route::prefix('kost')->name('kost.')->middleware('pemilik')->group(function () {
        Route::get('/', [KostController::class, 'index'])->name('index');
        Route::get('/create', [KostController::class, 'create'])->name('create');
        Route::post('/', [KostController::class, 'store'])->name('store');
        Route::get('/{kost}', [KostController::class, 'show'])->name('show');
        Route::get('/{kost}/edit', [KostController::class, 'edit'])->name('edit');
        Route::put('/{kost}', [KostController::class, 'update'])->name('update');
        Route::delete('/{kost}', [KostController::class, 'destroy'])->name('destroy');
        
        // Kost settings routes
        Route::get('/{kost}/settings', [KostSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/{kost}/settings', [KostSettingController::class, 'update'])->name('settings.update');
    });

    // Kamar management routes (for pemilik only)
    Route::prefix('kamar')->name('kamar.')->middleware('pemilik')->group(function () {
        Route::get('/', [KamarController::class, 'index'])->name('index');
        Route::get('/create', [KamarController::class, 'create'])->name('create');
        Route::post('/', [KamarController::class, 'store'])->name('store');
        Route::get('/{kamar}', [KamarController::class, 'show'])->name('show');
        Route::get('/{kamar}/edit', [KamarController::class, 'edit'])->name('edit');
        Route::put('/{kamar}', [KamarController::class, 'update'])->name('update');
        Route::delete('/{kamar}', [KamarController::class, 'destroy'])->name('destroy');
    });

    // Makanan management routes (for pemilik only)
    Route::prefix('makanan')->name('makanan.')->middleware('pemilik')->group(function () {
        Route::get('/', [MakananController::class, 'index'])->name('index');
        Route::get('/create', [MakananController::class, 'create'])->name('create');
        Route::post('/', [MakananController::class, 'store'])->name('store');
        Route::get('/{makanan}', [MakananController::class, 'show'])->name('show');
        Route::get('/{makanan}/edit', [MakananController::class, 'edit'])->name('edit');
        Route::put('/{makanan}', [MakananController::class, 'update'])->name('update');
        Route::delete('/{makanan}', [MakananController::class, 'destroy'])->name('destroy');
    });

    // Tenant food menu browsing routes (for penyewa) - Redirect to orders
    Route::prefix('food')->name('food.')->middleware('auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('orders.create');
        })->name('index');
        Route::get('/all', function () {
            return redirect()->route('orders.create');
        })->name('all');
    });

    // Tenant food order routes (for penyewa)
    Route::prefix('orders')->name('orders.')->middleware('auth')->group(function () {
        Route::get('/', [TenantOrderController::class, 'index'])->name('index');
        Route::get('/create', [TenantOrderController::class, 'create'])->name('create');
        Route::post('/', [TenantOrderController::class, 'store'])->name('store');
        Route::get('/{order}', [TenantOrderController::class, 'show'])->name('show');
        Route::patch('/{order}/cancel', [TenantOrderController::class, 'cancel'])->name('cancel');
        Route::patch('/{order}/complete', [TenantOrderController::class, 'complete'])->name('complete');
        Route::get('/{order}/pay', [TenantOrderController::class, 'pay'])->name('pay');
        Route::get('/{order}/payment/success', [TenantOrderController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/{order}/payment/failed', [TenantOrderController::class, 'paymentFailed'])->name('payment.failed');
    });

    // Owner food order management routes (for pemilik)
    Route::prefix('owner-orders')->name('owner.orders.')->middleware('pemilik')->group(function () {
        Route::get('/', [OwnerOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OwnerOrderController::class, 'show'])->name('show');
        Route::post('/{order}/process', [OwnerOrderController::class, 'process'])->name('process');
        Route::post('/{order}/deliver', [OwnerOrderController::class, 'deliver'])->name('deliver');
        Route::post('/{order}/cancel', [OwnerOrderController::class, 'cancel'])->name('cancel');
        Route::get('/stats/overview', [OwnerOrderController::class, 'stats'])->name('stats');
    });

    // Tenant galon order routes (for penyewa)
    Route::prefix('galon-orders')->name('galon.orders.')->middleware('auth')->group(function () {
        Route::get('/', [TenantGalonController::class, 'index'])->name('index');
        Route::get('/create', [TenantGalonController::class, 'create'])->name('create');
        Route::post('/', [TenantGalonController::class, 'store'])->name('store');
        Route::get('/{order}', [TenantGalonController::class, 'show'])->name('show');
        Route::patch('/{order}/cancel', [TenantGalonController::class, 'cancel'])->name('cancel');
        Route::patch('/{order}/complete', [TenantGalonController::class, 'complete'])->name('complete');
        Route::get('/{order}/pay', [TenantGalonController::class, 'pay'])->name('pay');
        Route::get('/{order}/payment/success', [TenantGalonController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/{order}/payment/failed', [TenantGalonController::class, 'paymentFailed'])->name('payment.failed');
    });

    // Owner galon order management routes (for pemilik)
    Route::prefix('owner-galon')->name('owner.galon.')->middleware('pemilik')->group(function () {
        Route::get('/', [OwnerGalonController::class, 'index'])->name('index');
        Route::get('/{order}', [OwnerGalonController::class, 'show'])->name('show');
        Route::post('/{order}/process', [OwnerGalonController::class, 'process'])->name('process');
        Route::post('/{order}/deliver', [OwnerGalonController::class, 'deliver'])->name('deliver');
        Route::post('/{order}/cancel', [OwnerGalonController::class, 'cancel'])->name('cancel');
        Route::get('/stats/overview', [OwnerGalonController::class, 'stats'])->name('stats');
    });

    // Galon catalog management routes (for pemilik)
    Route::prefix('galon')->name('galon.')->middleware('pemilik')->group(function () {
        Route::get('/', [GalonController::class, 'index'])->name('index');
        Route::get('/create', [GalonController::class, 'create'])->name('create');
        Route::post('/', [GalonController::class, 'store'])->name('store');
        Route::get('/{galonType}', [GalonController::class, 'show'])->name('show');
        Route::get('/{galonType}/edit', [GalonController::class, 'edit'])->name('edit');
        Route::put('/{galonType}', [GalonController::class, 'update'])->name('update');
        Route::delete('/{galonType}', [GalonController::class, 'destroy'])->name('destroy');
    });

    // Galon catalog browsing route (for penyewa) - redirects to galon orders
    Route::get('/galon-catalog', function () {
        return redirect()->route('galon.orders.create');
    })->name('galon.catalog')->middleware('auth');

    // Pesan routes (for tenants)
    Route::prefix('pesan')->name('pesan.')->middleware('auth')->group(function () {
        Route::get('/', [PesanController::class, 'index'])->name('index');
        Route::get('/create/{kamarId}', [PesanController::class, 'create'])->name('create');
        Route::post('/', [PesanController::class, 'store'])->name('store');
        Route::get('/{pesan}', [PesanController::class, 'show'])->name('show');
        Route::patch('/{pesan}/cancel', [PesanController::class, 'cancel'])->name('cancel');
    });

    // Pesan owner routes (for pemilik - verification)
    Route::prefix('pesan-owner')->name('pesan.owner.')->middleware('pemilik')->group(function () {
        Route::get('/', [PesanOwnerController::class, 'index'])->name('index');
        Route::get('/{pesan}', [PesanOwnerController::class, 'show'])->name('show');
        Route::post('/{pesan}/extend', [PesanOwnerController::class, 'extendDuration'])->name('extend');
        Route::post('/{pesan}/complete', [PesanOwnerController::class, 'markCompleted'])->name('complete');
        Route::get('/stats/overview', [PesanOwnerController::class, 'stats'])->name('stats');
    });

    // Midtrans routes (authenticated)
    Route::get('/pesan/{pesan}/pay', [MidtransController::class, 'pay'])->name('midtrans.pay')->middleware('auth');
    Route::get('/pesan/{pesan}/pay/success', [MidtransController::class, 'success'])->name('midtrans.success')->middleware('auth');
    Route::get('/pesan/{pesan}/pay/failed', [MidtransController::class, 'failed'])->name('midtrans.failed')->middleware('auth');

    // Tenant laundry order routes (for penyewa)
    Route::prefix('laundry-orders')->name('laundry.orders.')->middleware('auth')->group(function () {
        Route::get('/', [TenantLaundryController::class, 'index'])->name('index');
        Route::get('/create', [TenantLaundryController::class, 'create'])->name('create');
        Route::post('/', [TenantLaundryController::class, 'store'])->name('store');
        Route::get('/{order}', [TenantLaundryController::class, 'show'])->name('show');
        Route::patch('/{order}/cancel', [TenantLaundryController::class, 'cancel'])->name('cancel');
        Route::patch('/{order}/complete', [TenantLaundryController::class, 'complete'])->name('complete');
        Route::get('/{order}/pay', [TenantLaundryController::class, 'pay'])->name('pay');
        Route::get('/{order}/payment/success', [TenantLaundryController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/{order}/payment/failed', [TenantLaundryController::class, 'paymentFailed'])->name('payment.failed');
    });

    // Owner laundry order management routes (for pemilik)
    Route::prefix('owner-laundry')->name('owner.laundry.')->middleware('pemilik')->group(function () {
        Route::get('/', [OwnerLaundryController::class, 'index'])->name('index');
        Route::get('/{order}', [OwnerLaundryController::class, 'show'])->name('show');
        Route::post('/{order}/weigh', [OwnerLaundryController::class, 'weigh'])->name('weigh');
        Route::post('/{order}/set-estimation', [OwnerLaundryController::class, 'setEstimation'])->name('set-estimation');
        Route::post('/{order}/upload-finished', [OwnerLaundryController::class, 'uploadFinished'])->name('upload-finished');
        Route::post('/{order}/cancel', [OwnerLaundryController::class, 'cancel'])->name('cancel');
        Route::get('/stats/overview', [OwnerLaundryController::class, 'stats'])->name('stats');
    });

    // Laundry catalog management routes (for pemilik)
    Route::prefix('laundry')->name('laundry.')->middleware('pemilik')->group(function () {
        Route::get('/', [LaundryController::class, 'index'])->name('index');
        Route::get('/create', [LaundryController::class, 'create'])->name('create');
        Route::post('/', [LaundryController::class, 'store'])->name('store');
        Route::get('/{laundryType}', [LaundryController::class, 'show'])->name('show');
        Route::get('/{laundryType}/edit', [LaundryController::class, 'edit'])->name('edit');
        Route::put('/{laundryType}', [LaundryController::class, 'update'])->name('update');
        Route::delete('/{laundryType}', [LaundryController::class, 'destroy'])->name('destroy');
    });

    // Laundry catalog browsing route (for penyewa) - redirects to laundry orders
    Route::get('/laundry-catalog', function () {
        return redirect()->route('laundry.orders.create');
    })->name('laundry.catalog')->middleware('auth');
});

// Midtrans callback (NO auth - accessible by Midtrans server)
Route::post('/api/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');
