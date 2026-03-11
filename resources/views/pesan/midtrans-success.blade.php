@extends('layouts.app')

@section('title', 'Status Pembayaran')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        @if($paymentStatus === 'settlement' || $paymentStatus === 'capture')
            {{-- Payment Successful --}}
            <div style="font-size: 80px; margin-bottom: 20px;">✅</div>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 15px;">Pembayaran Berhasil!</h1>
            <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                Terima kasih! Pembayaran Anda telah berhasil diproses.
            </p>
            
            <div style="background: #d4edda; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #28a745;">
                <p style="font-size: 13px; color: #155724; margin: 0;">
                    ✅ <strong>Status:</strong> Pembayaran berhasil diverifikasi otomatis.
                </p>
            </div>
        @elseif($paymentStatus === 'pending')
            {{-- Payment Pending --}}
            <div style="font-size: 80px; margin-bottom: 20px;">⏳</div>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 15px;">Pembayaran Pending</h1>
            <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran.
            </p>
            
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #ffc107;">
                <p style="font-size: 13px; color: #856404; margin: 0;">
                    ⏳ <strong>Status:</strong> Menunggu pembayaran. Selesaikan sebelum batas waktu.
                </p>
            </div>
        @elseif(in_array($paymentStatus, ['cancel', 'deny', 'expire']))
            {{-- Payment Failed/Cancelled --}}
            <div style="font-size: 80px; margin-bottom: 20px;">❌</div>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 15px;">Pembayaran Gagal</h1>
            <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                Pembayaran Anda dibatalkan atau gagal.
            </p>
            
            <div style="background: #f8d7da; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #dc3545;">
                <p style="font-size: 13px; color: #721c24; margin: 0;">
                    ❌ <strong>Status:</strong> {{ ucfirst($paymentStatus) }}
                </p>
            </div>
        @else
            {{-- Unknown Status --}}
            <div style="font-size: 80px; margin-bottom: 20px;">ℹ️</div>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 15px;">Status Pembayaran</h1>
            <p style="color: #666; font-size: 16px; margin-bottom: 30px;">
                Status pembayaran Anda sedang diproses.
            </p>
        @endif

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
            @if($pembayaran)
                <p style="font-size: 12px; color: #999; margin-top: 10px; font-family: monospace;">
                    Order ID: {{ $pembayaran->order_id }}
                </p>
            @endif
        </div>

        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('pesan.index') }}" class="btn" style="min-width: 150px;">
                📋 Lihat Pemesanan Saya
            </a>
            <a href="{{ route('pesan.show', $pesan->id_pesan) }}" class="btn" style="background: #6c757d; min-width: 120px;">
                👁️ Detail
            </a>
            @if($paymentStatus === 'pending')
                <a href="{{ route('midtrans.pay', $pesan->id_pesan) }}" class="btn" style="background: #43e97b; min-width: 120px;">
                    💳 Bayar Lagi
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
