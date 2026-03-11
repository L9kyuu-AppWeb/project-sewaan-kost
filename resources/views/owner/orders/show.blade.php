@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id_pesanan_makanan)

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('owner.orders.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Pesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Pesanan #{{ $order->id_pesanan_makanan }}</h1>
                        <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: {{ $order->statusBadge }};">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                    <p style="color: #666; font-size: 16px;">🏢 {{ $order->kost->nama_kost }}</p>
                </div>
            </div>

            {{-- Pemesan Info --}}
            <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">👤 Informasi Pemesan</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #666; margin-bottom: 5px;">Nama</p>
                        <p style="font-size: 15px; color: #333; font-weight: 600; margin: 0;">{{ $order->penyewa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: #666; margin-bottom: 5px;">Email</p>
                        <p style="font-size: 15px; color: #333; margin: 0;">{{ $order->penyewa->email }}</p>
                    </div>
                    @if ($order->penyewa->no_hp)
                        <div>
                            <p style="font-size: 13px; color: #666; margin-bottom: 5px;">No HP</p>
                            <p style="font-size: 15px; color: #333; margin: 0;">{{ $order->penyewa->no_hp }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div style="margin: 30px 0;">
                <h3 style="font-size: 18px; color: #333; margin-bottom: 15px; font-weight: 600;">📦 Item Pesanan</h3>
                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    @foreach ($order->details as $detail)
                        <div style="display: flex; justify-content: space-between; align-items: start; padding: 15px 0; border-bottom: {{ $loop->last ? 'none' : '1px solid #e0e0e0' }};">
                            <div style="flex: 1;">
                                <p style="font-size: 15px; color: #333; font-weight: 600; margin: 0;">{{ $detail->makanan->nama_makanan }}</p>
                                <p style="font-size: 13px; color: #666; margin: 5px 0 0;">
                                    {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </p>
                                @if ($detail->catatan_item)
                                    <p style="font-size: 12px; color: #999; margin: 5px 0 0;">📝 {{ $detail->catatan_item }}</p>
                                @endif
                            </div>
                            <strong style="font-size: 15px; color: #970747;">{{ $detail->formatted_subtotal }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📊 Total Item</p>
                    <p style="font-size: 24px; color: #333; font-weight: 700; margin: 0;">{{ $order->total_item }} porsi</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">💰 Total Harga</p>
                    <p style="font-size: 24px; color: #970747; font-weight: 700; margin: 0;">{{ $order->formatted_total_harga }}</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📅 Tanggal Pesan</p>
                    <p style="font-size: 16px; color: #333; font-weight: 600; margin: 0;">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            @if ($order->catatan)
                <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <p style="font-size: 14px; color: #856404; margin: 0;">
                        <strong>📝 Catatan Pesanan:</strong> {{ $order->catatan }}
                    </p>
                </div>
            @endif

            {{-- Payment Info --}}
            @if ($order->latestPembayaran)
                <div style="margin-top: 20px; padding: 15px; background: {{ $order->latestPembayaran->transaction_status === 'settlement' ? '#d4edda' : '#fff3cd' }}; border-radius: 8px; border-left: 4px solid {{ $order->latestPembayaran->transaction_status === 'settlement' ? '#28a745' : '#ffc107' }};">
                    <p style="font-size: 14px; color: #856404; margin: 0;">
                        <strong>💳 Status Pembayaran:</strong> 
                        {{ ucfirst($order->latestPembayaran->transaction_status ?? 'belum_bayar') }}
                        @if ($order->latestPembayaran->transaction_time)
                            ({{ $order->latestPembayaran->transaction_time->format('d M Y, H:i') }})
                        @endif
                    </p>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; gap: 10px; flex-wrap: wrap;">
                @if ($order->status_antar == 'menunggu_bayar')
                    <form action="{{ route('owner.orders.process', $order->id_pesanan_makanan) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai diproses?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #4facfe;">
                            ⚙️ Proses Pesanan
                        </button>
                    </form>
                    <form action="{{ route('owner.orders.cancel', $order->id_pesanan_makanan) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                @elseif ($order->status_antar == 'diproses')
                    <form action="{{ route('owner.orders.deliver', $order->id_pesanan_makanan) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai dikirim?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #43e97b;">
                            🚚 Kirim Pesanan
                        </button>
                    </form>
                    <form action="{{ route('owner.orders.cancel', $order->id_pesanan_makanan) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                @else
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ Pesanan {{ $order->statusLabel }} - Tidak ada aksi lebih lanjut
                    </span>
                @endif
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; color: #999; font-size: 13px; flex-wrap: wrap; gap: 10px;">
                <span>ID Order: {{ $order->orderan_id }}</span>
                <span>Terakhir diupdate: {{ $order->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
