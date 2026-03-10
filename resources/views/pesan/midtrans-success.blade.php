@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="font-size: 80px; margin-bottom: 20px;">✅</div>
        <h1 style="font-size: 24px; color: #222; margin-bottom: 15px;">Pembayaran Berhasil!</h1>
        <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
            Terima kasih! Pembayaran Anda sedang diproses.
        </p>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; text-align: left;">
            <p style="font-size: 14px; color: #666; margin-bottom: 10px;">Detail Pemesanan:</p>
            <p style="font-size: 16px; color: #222; font-weight: 600; margin-bottom: 5px;">
                {{ $pesan->kamar->kost->nama_kost }}
            </p>
            <p style="font-size: 14px; color: #666; margin-bottom: 5px;">
                Kamar {{ $pesan->kamar->nomor_kamar }} • {{ $pesan->durasi_bulan }} bulan
            </p>
            <p style="font-size: 18px; color: #970747; font-weight: 700;">
                {{ $pesan->formattedTotalHarga }}
            </p>
        </div>

        <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #2196F3;">
            <p style="font-size: 13px; color: #1565C0; margin: 0;">
                ℹ️ Pembayaran Anda akan diverifikasi oleh pemilik dalam 1-3 hari kerja. Anda akan menerima notifikasi melalui email.
            </p>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="{{ route('pesan.index') }}" class="btn" style="min-width: 150px;">
                📋 Lihat Pemesanan Saya
            </a>
            <a href="{{ route('pesan.show', $pesan->id_pesan) }}" class="btn" style="background: #6c757d; min-width: 120px;">
                👁️ Detail
            </a>
        </div>
    </div>
</div>
@endsection
