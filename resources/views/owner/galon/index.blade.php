@extends('layouts.app')

@section('title', 'Kelola Pesanan Galon')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">💧 Kelola Pesanan Galon</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Kelola semua pesanan galon dari kost Anda</p>
        </div>
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

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form action="{{ route('owner.galon.index') }}" method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label for="kost_id" style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Filter berdasarkan Kost:</label>
                <select name="kost_id" id="kost_id" onchange="this.form.submit()" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Kost</option>
                    @foreach ($kosts as $k)
                        <option value="{{ $k->id_kost }}" {{ $kostId == $k->id_kost ? 'selected' : '' }}>
                            {{ $k->nama_kost }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div style="flex: 1; min-width: 200px;">
                <label for="status" style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Filter berdasarkan Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_bayar" {{ $status == 'menunggu_bayar' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="diambil" {{ $status == 'diambil' ? 'selected' : '' }}>Diambil</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <a href="{{ route('owner.galon.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                Reset
            </a>
        </form>
    </div>

    @if ($orders->count() > 0)
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Tanggal</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Order ID</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Pemesan</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Jenis Air</th>
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
                                <strong>{{ $order->penyewa->nama_lengkap ?? 'N/A' }}</strong>
                            </td>
                            <td style="padding: 15px; font-size: 14px; color: #666;">
                                {{ $order->galonType->nama_air ?? 'N/A' }}
                            </td>
                            <td style="padding: 15px; text-align: right; font-size: 14px; font-weight: 600; color: #4facfe;">
                                {{ $order->formatted_total_bayar }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; color: white; background: {{ $order->statusBadge }};">
                                    {{ $order->statusLabel }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('owner.galon.show', $order->id_order_galon) }}" 
                                       style="padding: 6px 10px; background: #4facfe; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        👁️
                                    </a>
                                    
                                    @if ($order->status_galon == 'menunggu_bayar')
                                        <form action="{{ route('owner.galon.process', $order->id_order_galon) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tandai pesanan ini sebagai diproses?')">
                                            @csrf
                                            <button type="submit" style="padding: 6px 10px; background: #00f2fe; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                ⚙️
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if (in_array($order->status_galon, ['menunggu_bayar', 'diproses']))
                                        <form action="{{ route('owner.galon.cancel', $order->id_order_galon) }}" method="POST" style="display: inline;" onsubmit="return confirm('Batalkan pesanan ini?')">
                                            @csrf
                                            <button type="submit" style="padding: 6px 10px; background: #f5576c; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                🚫
                                            </button>
                                        </form>
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
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">💧</span>
            <h3 style="color: #4facfe; margin-bottom: 10px;">Belum Ada Pesanan</h3>
            <p style="color: #666;">Belum ada pesanan galon untuk kost Anda.</p>
        </div>
    @endif
</div>
@endsection
