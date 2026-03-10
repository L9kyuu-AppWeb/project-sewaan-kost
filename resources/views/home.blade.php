@extends('layouts.app')

@section('title', 'Home - Sewa An Kost')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <div style="background: white; border-radius: 12px; padding: 60px 40px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="margin-bottom: 40px;">
            <span style="font-size: 80px; display: block; margin-bottom: 20px;">🏠</span>
            <h1 style="font-size: 36px; color: #222; margin-bottom: 15px;">Sewa An Kost</h1>
            <p style="color: #666; font-size: 18px; line-height: 1.6; max-width: 600px; margin: 0 auto;">
                Sistem Manajemen Sewa Kost Modern<br>
                Kelola properti kost Anda dengan mudah dan efisien
            </p>
        </div>

        @guest
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('kost-public.index') }}" class="btn" style="min-width: 150px;">
                    🔍 Cari Kost
                </a>
                <a href="{{ route('login') }}" class="btn" style="background: white; color: #970747; border: 2px solid #970747; min-width: 150px;">
                    🔐 Login
                </a>
                <a href="{{ route('register') }}" class="btn" style="background: white; color: #970747; border: 2px solid #970747; min-width: 150px;">
                    📝 Register
                </a>
            </div>

            <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #eee;">
                <h2 style="font-size: 24px; color: #222; margin-bottom: 30px;">Kenapa Memilih Kami?</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; max-width: 800px; margin: 0 auto;">
                    <div style="padding: 20px;">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">🏢</span>
                        <h3 style="font-size: 16px; color: #222; margin-bottom: 8px;">Kelola Kost Mudah</h3>
                        <p style="font-size: 14px; color: #666;">Dashboard intuitif untuk mengelola semua properti kost Anda</p>
                    </div>
                    <div style="padding: 20px;">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">📊</span>
                        <h3 style="font-size: 16px; color: #222; margin-bottom: 8px;">Laporan Lengkap</h3>
                        <p style="font-size: 14px; color: #666;">Pantau okupansi dan pembayaran dengan laporan real-time</p>
                    </div>
                    <div style="padding: 20px;">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">📱</span>
                        <h3 style="font-size: 16px; color: #222; margin-bottom: 8px;">Akses Mobile</h3>
                        <p style="font-size: 14px; color: #666;">Akses kapan saja dari perangkat mobile Anda</p>
                    </div>
                </div>
            </div>
        @else
            <div style="margin-top: 30px;">
                <a href="{{ auth()->user()->role === 'pemilik' ? route('dashboard.pemilik') : route('dashboard.penyewa') }}" class="btn" style="min-width: 200px;">
                    📊 Buka Dashboard
                </a>
            </div>
        @endguest

        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="font-size: 12px; color: #999;">
                © 2026 Sewa An Kost. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection
