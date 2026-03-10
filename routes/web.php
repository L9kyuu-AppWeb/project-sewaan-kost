<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\KostPublicController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PesanOwnerController;
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
        $stats = [
            'total_kost' => \App\Models\Kost::where('id_pemilik', $user->id_user)->count(),
            'total_kamar' => \App\Models\Kamar::whereHas('kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->count(),
            'kamar_terisi' => \App\Models\Kamar::whereHas('kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_kamar', 'terisi')->count(),
            'penyewa_aktif' => \App\Models\Pesan::whereHas('kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_pesan', 'aktif')->count(),
            'pending_payments' => \App\Models\Pembayaran::whereHas('pesan.kamar.kost', function ($q) use ($user) {
                $q->where('id_pemilik', $user->id_user);
            })->where('status_verifikasi', 'pending')->count(),
        ];
        
        return view('dashboard.pemilik', compact('stats'));
    })->name('dashboard.pemilik');

    Route::get('/dashboard/penyewa', function () {
        if (auth()->user()->role !== 'penyewa') {
            return redirect('/dashboard/pemilik');
        }
        
        $user = auth()->user();
        $stats = [
            'total_pemesanan' => \App\Models\Pesan::where('id_penyewa', $user->id_user)->count(),
            'pemesanan_aktif' => \App\Models\Pesan::where('id_penyewa', $user->id_user)->where('status_pesan', 'aktif')->count(),
            'pemesanan_pending' => \App\Models\Pesan::where('id_penyewa', $user->id_user)->whereIn('status_pesan', ['menunggu_pembayaran', 'proses_verifikasi'])->count(),
            'kamar_saat_ini' => \App\Models\Pesan::where('id_penyewa', $user->id_user)->where('status_pesan', 'aktif')->first()?->kamar,
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

    // Pesan routes (for tenants)
    Route::prefix('pesan')->name('pesan.')->middleware('auth')->group(function () {
        Route::get('/', [PesanController::class, 'index'])->name('index');
        Route::get('/create/{kamarId}', [PesanController::class, 'create'])->name('create');
        Route::post('/', [PesanController::class, 'store'])->name('store');
        Route::get('/{pesan}', [PesanController::class, 'show'])->name('show');
        Route::get('/{pesan}/upload-payment', [PesanController::class, 'uploadPayment'])->name('upload-payment');
        Route::post('/{pesan}/upload-payment', [PesanController::class, 'storePayment'])->name('store-payment');
        Route::patch('/{pesan}/cancel', [PesanController::class, 'cancel'])->name('cancel');
    });

    // Pesan owner routes (for pemilik - verification)
    Route::prefix('pesan-owner')->name('pesan.owner.')->middleware('pemilik')->group(function () {
        Route::get('/', [PesanOwnerController::class, 'index'])->name('index');
        Route::get('/{pesan}', [PesanOwnerController::class, 'show'])->name('show');
        Route::get('/{pesan}/verify-payment', [PesanOwnerController::class, 'verifyPayment'])->name('verify-payment');
        Route::post('/pembayaran/{pembayaran}/verify', [PesanOwnerController::class, 'processVerification'])->name('process-verification');
        Route::post('/{pesan}/extend', [PesanOwnerController::class, 'extendDuration'])->name('extend');
        Route::post('/{pesan}/complete', [PesanOwnerController::class, 'markCompleted'])->name('complete');
        Route::get('/stats/overview', [PesanOwnerController::class, 'stats'])->name('stats');
    });

    // Midtrans routes
    Route::post('/api/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');
    Route::get('/pesan/{pesan}/pay', [MidtransController::class, 'pay'])->name('midtrans.pay')->middleware('auth');
    Route::get('/pesan/{pesan}/pay/success', [MidtransController::class, 'success'])->name('midtrans.success')->middleware('auth');
    Route::get('/pesan/{pesan}/pay/failed', [MidtransController::class, 'failed'])->name('midtrans.failed')->middleware('auth');
});
