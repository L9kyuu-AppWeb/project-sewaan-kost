@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 40px 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center;">
        <div style="width: 80px; height: 80px; background: #f5576c; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
            <span style="font-size: 40px; color: white;">❌</span>
        </div>
        
        <h1 style="font-size: 24px; color: #333; margin-bottom: 10px;">Pembayaran Gagal</h1>
        <p style="color: #666; font-size: 15px; margin-bottom: 25px;">
            Maaf, pembayaran Anda tidak dapat diproses
        </p>

        <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #666; font-size: 14px;">Nomor Pesanan</span>
                <strong style="color: #333; font-size: 14px;">#{{ $order->id_order_laundry }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #666; font-size: 14px;">Order ID</span>
                <strong style="color: #333; font-size: 14px;">{{ $order->orderan_id }}</strong>
            </div>
        </div>

        <div style="padding: 15px; background: #ffebee; border-radius: 8px; margin-bottom: 25px;">
            <p style="font-size: 14px; color: #c62828; margin: 0;">
                ⚠️ Silakan coba pembayaran kembali atau batalkan pesanan
            </p>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            @if ($order->canBeCancelled())
                <form action="{{ route('laundry.orders.cancel', $order->id_order_laundry) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn" style="background: #f5576c;">
                        🚫 Batalkan Pesanan
                    </button>
                </form>
            @endif
            
            <a href="{{ route('laundry.orders.pay', $order->id_order_laundry) }}" class="btn" style="background: #43e97b; flex: 1; max-width: 200px;">
                💳 Coba Bayar Lagi
            </a>
        </div>

        <div style="margin-top: 15px;">
            <a href="{{ route('laundry.orders.index') }}" style="color: #666; text-decoration: none; font-size: 14px; font-weight: 600;">
                ← Kembali ke Riwayat Pesanan
            </a>
        </div>
    </div>
</div>
@endsection
