@extends('layouts.app')

@section('title', 'Detail Pesanan Galon #' . $order->id_order_galon)

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('galon.orders.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Pesanan #{{ $order->id_order_galon }}</h1>
                        <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: {{ $order->statusBadge }};">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                    <p style="color: #666; font-size: 16px;">🏢 {{ $order->kost->nama_kost }}</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @if ($order->isPendingPayment())
                        <a href="{{ route('galon.orders.pay', $order->id_order_galon) }}" class="btn" style="background: #43e97b;">
                            💳 Bayar Sekarang
                        </a>
                    @endif
                </div>
            </div>

            {{-- Order Info --}}
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Jenis Air</p>
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0;">{{ $order->galonType->nama_air ?? 'N/A' }}</h2>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Bayar</p>
                        <p style="font-size: 28px; font-weight: 700; margin: 0;">{{ $order->formatted_total_bayar }}</p>
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Galon Kosong</h3>
                    @if ($order->foto_kosong)
                        <img src="{{ asset('storage/' . $order->foto_kosong) }}" alt="Foto Galon Kosong" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #4facfe;">
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum ada foto</p>
                    @endif
                </div>

                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Galon Terisi</h3>
                    @if ($order->foto_terisi)
                        <img src="{{ asset('storage/' . $order->foto_terisi) }}" alt="Foto Galon Terisi" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #43e97b;">
                        <p style="color: #43e97b; font-size: 13px; margin-top: 10px; text-align: center;">✓ Sudah diantar oleh pengelola</p>
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum diantar</p>
                    @endif
                </div>
            </div>

            {{-- Order Summary --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📅 Tanggal Pesan</p>
                    <p style="font-size: 16px; color: #333; font-weight: 600; margin: 0;">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📝 Order ID</p>
                    <p style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">{{ $order->orderan_id }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; gap: 10px; flex-wrap: wrap;">
                @if ($order->canBeCancelled())
                    <form action="{{ route('galon.orders.cancel', $order->id_order_galon) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if ($order->status_galon === 'diambil')
                    <form action="{{ route('galon.orders.complete', $order->id_order_galon) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa galon sudah Anda terima?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn" style="background: #43e97b;">
                            ✅ Konfirmasi Diterima
                        </button>
                    </form>
                @endif

                @if (!$order->canBeCancelled() && $order->status_galon !== 'diambil' && !$order->isPendingPayment())
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ Pesanan {{ $order->statusLabel }} - Tidak ada aksi lebih lanjut
                    </span>
                @endif
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; color: #999; font-size: 13px; flex-wrap: wrap; gap: 10px;">
                <span>Terakhir diupdate: {{ $order->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
