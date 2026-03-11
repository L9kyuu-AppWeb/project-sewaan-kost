@extends('layouts.app')

@section('title', 'Pemesanan Saya - Sewa An Kost')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">📋 Pemesanan Saya</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Riwayat pemesanan kamar Anda</p>
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

    <!-- Filter -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form action="{{ route('pesan.index') }}" method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">Filter Status:</label>
                <select name="status" onchange="this.form.submit()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="proses_verifikasi" {{ request('status') === 'proses_verifikasi' ? 'selected' : '' }}>Proses Verifikasi</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <a href="{{ route('pesan.index') }}" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">Reset</a>
        </form>
    </div>

    @if ($pesanans->count() > 0)
        <div style="display: grid; gap: 20px;">
            @foreach ($pesanans as $pesan)
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <h3 style="font-size: 18px; color: #222; margin-bottom: 5px;">
                                Kamar {{ $pesan->kamar->nomor_kamar }} - {{ $pesan->kamar->kost->nama_kost }}
                            </h3>
                            <p style="font-size: 13px; color: #666;">
                                📅 {{ $pesan->tgl_mulai->format('d M Y') }} - {{ $pesan->tgl_selesai->format('d M Y') }} 
                                ({{ $pesan->durasi_bulan }} bulan)
                            </p>
                        </div>
                        <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: {{ $pesan->statusBadge }};">
                            {{ $pesan->statusLabel }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <div>
                            <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Total Harga</p>
                            <p style="font-size: 18px; color: #970747; font-weight: 700;">{{ $pesan->formattedTotalHarga }}</p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Tanggal Pesan</p>
                            <p style="font-size: 14px; color: #333;">{{ $pesan->tgl_pemesanan->format('d M Y') }}</p>
                        </div>
                        @if ($pesan->latestPayment)
                            <div>
                                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Status Pembayaran</p>
                                <p style="font-size: 14px; color: #333;">{{ $pesan->latestPayment->statusLabel }}</p>
                            </div>
                        @endif
                    </div>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('pesan.show', $pesan->id_pesan) }}" class="btn" style="min-width: 120px; padding: 10px 16px; font-size: 13px;">
                            👁️ Detail
                        </a>

                        @if ($pesan->isPendingPayment())
                            <a href="{{ route('midtrans.pay', $pesan->id_pesan) }}" class="btn" style="background: #43e97b; min-width: 150px; padding: 10px 16px; font-size: 13px;">
                                💳 Bayar via Midtrans
                            </a>
                            <form action="{{ route('pesan.cancel', $pesan->id_pesan) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn" style="background: #f5576c; min-width: 120px; padding: 10px 16px; font-size: 13px;">
                                    ❌ Batal
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $pesanans->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <span style="font-size: 80px; display: block; margin-bottom: 20px;">📋</span>
            <h3 style="color: #222; margin-bottom: 10px; font-size: 22px;">Belum Ada Pemesanan</h3>
            <p style="color: #666; margin-bottom: 25px;">Anda belum memiliki pemesanan kamar.</p>
            <a href="{{ route('kost-public.index') }}" class="btn" style="min-width: 150px;">🔍 Cari Kost</a>
        </div>
    @endif
</div>
@endsection
