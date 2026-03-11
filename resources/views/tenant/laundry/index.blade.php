@extends('layouts.app')

@section('title', 'Riwayat Pesanan Laundry')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">👕 Riwayat Pesanan Laundry</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Lihat semua pesanan laundry Anda</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if (!$activePesanan || !$activePesanan->kamar)
        <div style="background: white; border-radius: 12px; padding: 60px 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">⚠️</span>
            <h3 style="color: #970747; margin-bottom: 10px;">Anda Belum Memiliki Pemesanan Kost Aktif</h3>
            <p style="color: #666; margin-bottom: 20px;">Silakan pesan kost terlebih dahulu untuk dapat memesan laundry.</p>
            <a href="{{ route('kost-public.index') }}" class="btn" style="width: auto; padding: 12px 24px; display: inline-block;">
                🔍 Cari Kost
            </a>
        </div>
    @endif

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form action="{{ route('laundry.orders.index') }}" method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label for="status" style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Filter berdasarkan Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_jemput" {{ request('status') == 'menunggu_jemput' ? 'selected' : '' }}>Menunggu Jemput</option>
                    <option value="menunggu_bayar" {{ request('status') == 'menunggu_bayar' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="sedang_dicuci" {{ request('status') == 'sedang_dicuci' ? 'selected' : '' }}>Sedang Dicuci</option>
                    <option value="siap_antar" {{ request('status') == 'siap_antar' ? 'selected' : '' }}>Siap Antar</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <a href="{{ route('laundry.orders.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                Reset
            </a>
        </form>
    </div>

    @if ($orders->count() > 0)
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Tanggal</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Order ID</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Layanan</th>
                        <th style="padding: 15px; text-align: center; font-size: 14px;">Berat</th>
                        <th style="padding: 15px; text-align: right; font-size: 14px;">Total</th>
                        <th style="padding: 15px; text-align: center; font-size: 14px;">Status</th>
                        <th style="padding: 15px; text-align: center; font-size: 14px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-size: 13px; color: #666;">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td style="padding: 15px; font-size: 12px; color: #333;">
                                <strong>{{ $order->orderan_id }}</strong>
                            </td>
                            <td style="padding: 15px; font-size: 14px; color: #333;">
                                {{ $order->laundryType->nama_layanan ?? 'N/A' }}
                            </td>
                            <td style="padding: 15px; text-align: center; font-size: 14px; color: #666;">
                                {{ $order->berat_kg ? $order->berat_kg . ' kg' : '-' }}
                            </td>
                            <td style="padding: 15px; text-align: right; font-size: 14px; font-weight: 600; color: #970747;">
                                {{ $order->formatted_total_harga }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; color: white; background: {{ $order->statusBadge }};">
                                    {{ $order->statusLabel }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ route('laundry.orders.show', $order->id_order_laundry) }}" 
                                       style="padding: 6px 12px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        👁️
                                    </a>
                                    @if ($order->isPendingPayment())
                                        <a href="{{ route('laundry.orders.pay', $order->id_order_laundry) }}" 
                                           style="padding: 6px 12px; background: #43e97b; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            💳
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    @else
        <div style="background: white; border-radius: 12px; padding: 60px 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">👕</span>
            <h3 style="color: #970747; margin-bottom: 10px;">Belum Ada Pesanan Laundry</h3>
            <p style="color: #666; margin-bottom: 20px;">Anda belum pernah memesan laundry.</p>
            @if ($activePesanan && $activePesanan->kamar)
                <a href="{{ route('laundry.orders.create') }}" class="btn" style="width: auto; padding: 12px 24px; display: inline-block;">
                    ➕ Pesan Laundry Pertama
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
