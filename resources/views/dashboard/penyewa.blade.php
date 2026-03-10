@extends('layouts.app')

@section('title', 'Dashboard Penyewa - Sewa An Kost')

@section('content')
<div class="container-full">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">🏠 Dashboard Penyewa</h1>
        <p style="color: rgba(255,255,255,0.9);">Selamat datang, {{ auth()->user()->nama_lengkap }}!</p>
    </div>

    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
                {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
            </div>
            <div>
                <h2 style="font-size: 18px; color: #333; margin-bottom: 3px;">{{ auth()->user()->nama_lengkap }}</h2>
                <p style="color: #666; font-size: 14px;">
                    {{ auth()->user()->email }} • {{ auth()->user()->no_hp }}
                </p>
                @if (auth()->user()->nik)
                    <p style="color: #999; font-size: 12px; margin-top: 5px;">NIK: {{ auth()->user()->nik }}</p>
                @endif
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <a href="{{ route('kost-public.index') }}" style="text-decoration: none; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 25px; border-radius: 12px; color: white; transition: transform 0.2s, box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(151,7,71,0.3)'" 
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">🔍</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">Cari Kost</h3>
            <p style="font-size: 13px; opacity: 0.9;">Temukan kost impian Anda</p>
        </a>
        <a href="{{ route('pesan.index') }}" style="text-decoration: none; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 12px; color: white; transition: transform 0.2s, box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(79,172,254,0.3)'" 
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">📋</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['total_pemesanan'] ?? 0 }}</h3>
            <p style="font-size: 13px; opacity: 0.9;">Total Pemesanan</p>
        </a>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">✅</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['pemesanan_aktif'] ?? 0 }}</h3>
            <p style="font-size: 13px; opacity: 0.9;">Pemesanan Aktif</p>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">⏳</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['pemesanan_pending'] ?? 0 }}</h3>
            <p style="font-size: 13px; opacity: 0.9;">Menunggu Pembayaran</p>
        </div>
        <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); padding: 25px; border-radius: 12px; color: #333;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">🏠</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['kamar_saat_ini']?->nomor_kamar ?? '-' }}</h3>
            <p style="font-size: 13px; opacity: 0.8;">Kamar Saat Ini</p>
        </div>
        <div style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">📊</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">-</h3>
            <p style="font-size: 13px; opacity: 0.9;">Status Pembayaran</p>
        </div>
    </div>
</div>
@endsection
