@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 40px 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
            <span style="font-size: 40px; color: white;">✅</span>
        </div>
        
        <h1 style="font-size: 24px; color: #333; margin-bottom: 10px;">Pembayaran Berhasil!</h1>
        <p style="color: #666; font-size: 15px; margin-bottom: 25px;">
            Laundry Anda sedang diproses
        </p>

        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white;">
            <p style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">{{ $order->laundryType->nama_layanan ?? 'Layanan Laundry' }}</p>
            @if ($order->berat_kg)
                <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">{{ $order->berat_kg }} kg</p>
            @endif
            <p style="font-size: 28px; font-weight: 700; margin: 0;">{{ $order->formatted_total_harga }}</p>
        </div>

        <div style="padding: 15px; background: #e8f5e9; border-radius: 8px; margin-bottom: 25px;">
            <p style="font-size: 14px; color: #2e7d32; margin: 0;">
                👕 Owner akan segera menyelesaikan laundry Anda
            </p>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="{{ route('laundry.orders.index') }}" class="btn" style="background: #970747; flex: 1; max-width: 200px;">
                Lihat Riwayat
            </a>
            <a href="{{ route('laundry.orders.create') }}" class="btn" style="background: #6c757d; flex: 1; max-width: 200px;">
                Pesan Lagi
            </a>
        </div>
    </div>
</div>
@endsection
