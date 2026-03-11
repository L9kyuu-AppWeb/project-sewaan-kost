@extends('layouts.app')

@section('title', 'Detail Pesanan Laundry #' . $order->id_order_laundry)

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('laundry.orders.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Pesanan #{{ $order->id_order_laundry }}</h1>
                        <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: {{ $order->statusBadge }};">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                    <p style="color: #666; font-size: 16px;">🏢 {{ $order->kost->nama_kost }}</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @if ($order->isPendingPayment())
                        <a href="{{ route('laundry.orders.pay', $order->id_order_laundry) }}" class="btn" style="background: #43e97b;">
                            💳 Bayar Sekarang
                        </a>
                    @endif
                </div>
            </div>

            {{-- Order Info --}}
            <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Layanan Laundry</p>
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0;">{{ $order->laundryType->nama_layanan ?? 'N/A' }}</h2>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Bayar</p>
                        <p style="font-size: 28px; font-weight: 700; margin: 0;">{{ $order->total_harga ? $order->formatted_total_harga : 'Menunggu berat' }}</p>
                    </div>
                </div>
            </div>

            {{-- Weight & Estimation Info --}}
            @if ($order->berat_kg)
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                        <p style="font-size: 13px; color: #666; margin-bottom: 5px;">⚖️ Berat Pakaian</p>
                        <p style="font-size: 24px; color: #333; font-weight: 700; margin: 0;">{{ $order->berat_kg }} kg</p>
                    </div>
                    @if ($order->tgl_selesai_estimasi)
                        <div style="background: {{ $order->isLate() ? '#ffebee' : '#e8f5e9' }}; border-radius: 12px; padding: 20px; border-left: 4px solid {{ $order->isLate() ? '#f5576c' : '#43e97b' }};">
                            <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📅 Estimasi Selesai</p>
                            <p style="font-size: 16px; color: {{ $order->isLate() ? '#c62828' : '#2e7d32' }}; font-weight: 600; margin: 0;">
                                {{ $order->tgl_selesai_estimasi->format('d M Y') }}
                            </p>
                            @if ($order->isLate())
                                <p style="font-size: 12px; color: #c62828; margin: 5px 0 0;">⚠️ Terlambat {{ $order->days_late }} hari</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- Photos --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Awal (Pakaian)</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Dari Anda (bukti kondisi awal)</p>
                    @if ($order->foto_awal)
                        <img src="{{ asset('storage/' . $order->foto_awal) }}" alt="Foto Awal" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #970747;">
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum ada foto</p>
                    @endif
                </div>

                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Selesai</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Dari owner (setelah selesai)</p>
                    @if ($order->foto_selesai)
                        <img src="{{ asset('storage/' . $order->foto_selesai) }}" alt="Foto Selesai" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #43e97b;">
                        @if ($order->tgl_selesai_aktual)
                            <p style="font-size: 12px; color: #43e97b; margin-top: 10px; text-align: center;">
                                ✓ Selesai pada {{ $order->tgl_selesai_aktual->format('d M Y, H:i') }}
                                @if ($order->isLate())
                                    <span style="color: #f5576c;">(Terlambat)</span>
                                @endif
                            </p>
                        @endif
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum diunggah</p>
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
                    <form action="{{ route('laundry.orders.cancel', $order->id_order_laundry) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if ($order->status_laundry === 'siap_antar')
                    <form action="{{ route('laundry.orders.complete', $order->id_order_laundry) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa laundry sudah Anda terima?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn" style="background: #43e97b;">
                            ✅ Konfirmasi Diterima
                        </button>
                    </form>
                @endif

                @if (!$order->canBeCancelled() && $order->status_laundry !== 'siap_antar' && !$order->isPendingPayment())
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
