@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="font-size: 80px; margin-bottom: 20px;">❌</div>
        <h1 style="font-size: 24px; color: #222; margin-bottom: 15px;">Pembayaran Gagal</h1>
        <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
            Maaf, pembayaran Anda tidak dapat diproses.
        </p>

        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #ffc107;">
            <p style="font-size: 13px; color: #856404; margin: 0;">
                ⚠️ Pembayaran Anda gagal atau dibatalkan. Silakan coba lagi atau hubungi pemilik kost untuk informasi lebih lanjut.
            </p>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('pesan.show', $pesan->id_pesan) }}" class="btn" style="min-width: 150px;">
                💳 Coba Lagi
            </a>
            <a href="{{ route('pesan.index') }}" class="btn" style="background: #6c757d; min-width: 120px;">
                📋 Pemesanan Saya
            </a>
            @if ($pesan->kamar->kost->pemilik->no_hp)
                <a href="https://wa.me/{{ $pesan->kamar->kost->pemilik->no_hp }}?text=Halo, saya {{ $pesan->penyewa->nama_lengkap }} mengalami kendala pembayaran untuk Kamar {{ $pesan->kamar->nomor_kamar }} di {{ $pesan->kamar->kost->nama_kost }}" 
                   target="_blank"
                   class="btn" style="background: #25D366; min-width: 150px;">
                    💬 Hubungi Pemilik
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
