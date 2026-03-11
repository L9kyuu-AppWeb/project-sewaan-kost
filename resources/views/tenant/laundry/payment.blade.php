@extends('layouts.app')

@section('title', 'Pembayaran Pesanan Laundry')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h1 style="font-size: 24px; color: #333; margin-bottom: 10px;">💳 Pembayaran Laundry</h1>
            <p style="color: #666;">Pesanan #{{ $order->id_order_laundry }}</p>
        </div>

        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white;">
            <div style="text-align: center;">
                <p style="font-size: 14px; opacity: 0.9; margin-bottom: 10px;">{{ $order->laundryType->nama_layanan ?? 'Layanan Laundry' }}</p>
                @if ($order->berat_kg)
                    <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">{{ $order->berat_kg }} kg x {{ $order->laundryType->formatted_harga_per_kg }}</p>
                @endif
                <p style="font-size: 32px; font-weight: 700; margin: 0;">{{ $order->formatted_total_harga }}</p>
            </div>
        </div>

        <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0;">
                <span style="color: #666; font-size: 14px;">Order ID</span>
                <strong style="color: #333; font-size: 14px;">{{ $order->orderan_id }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #666; font-size: 14px;">Status</span>
                <strong style="color: #970747; font-size: 14px;">{{ $order->statusLabel }}</strong>
            </div>
        </div>

        <div id="snap-container" style="text-align: center; padding: 20px 0;"></div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('laundry.orders.show', $order->id_order_laundry) }}" style="color: #666; text-decoration: none; font-size: 14px; font-weight: 600;">
                ← Kembali ke Detail Pesanan
            </a>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route("laundry.orders.payment.success", $order->id_order_laundry) }}';
            },
            onPending: function(result) {
                window.location.href = '{{ route("laundry.orders.show", $order->id_order_laundry) }}';
            },
            onError: function(error) {
                window.location.href = '{{ route("laundry.orders.payment.failed", $order->id_order_laundry) }}';
            },
            onClose: function() {
                window.location.href = '{{ route("laundry.orders.show", $order->id_order_laundry) }}';
            }
        });
    });
</script>
@endsection
