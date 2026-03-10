@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Sewa An Kost')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">💳 Verifikasi Pembayaran</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Kelola dan verifikasi pembayaran penyewa</p>
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

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">📋 Total Pemesanan</p>
            <p style="font-size: 32px; font-weight: 700;">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">⏳ Menunggu Pembayaran</p>
            <p style="font-size: 32px; font-weight: 700;">{{ $stats['menunggu_pembayaran'] ?? 0 }}</p>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">🔍 Proses Verifikasi</p>
            <p style="font-size: 32px; font-weight: 700;">{{ $stats['proses_verifikasi'] ?? 0 }}</p>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">✅ Aktif</p>
            <p style="font-size: 32px; font-weight: 700;">{{ $stats['aktif'] ?? 0 }}</p>
        </div>
        <div style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">⚠️ Pending Payment</p>
            <p style="font-size: 32px; font-weight: 700;">{{ $stats['pending_payments'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form action="{{ route('pesan.owner.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">Filter Kost:</label>
                <select name="kost_id" onchange="this.form.submit()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Kost</option>
                    @foreach ($kosts as $k)
                        <option value="{{ $k->id_kost }}" {{ request('kost_id') == $k->id_kost ? 'selected' : '' }}>
                            {{ $k->nama_kost }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
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

            <div style="display: flex; align-items: end;">
                <a href="{{ route('pesan.owner.index') }}" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if ($pesanans->count() > 0)
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">ID</th>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">Penyewa</th>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">Kamar</th>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">Durasi</th>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">Total</th>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">Status</th>
                        <th style="padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #222; border-bottom: 2px solid #dee2e6;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanans as $pesan)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 15px; font-size: 13px; color: #666;">
                                #{{ str_pad($pesan->id_pesan, 6, '0', STR_PAD_LEFT) }}
                            </td>
                            <td style="padding: 15px;">
                                <div>
                                    <p style="font-size: 14px; font-weight: 600; color: #222; margin: 0;">{{ $pesan->penyewa->nama_lengkap }}</p>
                                    <p style="font-size: 12px; color: #666; margin: 3px 0 0;">{{ $pesan->penyewa->email }}</p>
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                <div>
                                    <p style="font-size: 14px; color: #222; margin: 0;">Kamar {{ $pesan->kamar->nomor_kamar }}</p>
                                    <p style="font-size: 12px; color: #666; margin: 3px 0 0;">{{ $pesan->kamar->kost->nama_kost }}</p>
                                </div>
                            </td>
                            <td style="padding: 15px; font-size: 13px; color: #666;">
                                {{ $pesan->durasi_bulan }} bulan
                            </td>
                            <td style="padding: 15px;">
                                <p style="font-size: 15px; font-weight: 700; color: #970747; margin: 0;">
                                    {{ $pesan->formattedTotalHarga }}
                                </p>
                            </td>
                            <td style="padding: 15px;">
                                <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; color: white; background: {{ $pesan->statusBadge }};">
                                    {{ $pesan->statusLabel }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('pesan.owner.show', $pesan->id_pesan) }}" 
                                       style="padding: 6px 12px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        👁️ Detail
                                    </a>
                                    @if ($pesan->status_pesan === 'proses_verifikasi' && $pesan->latestPayment?->status_verifikasi === 'pending')
                                        <a href="{{ route('pesan.owner.verify-payment', $pesan->id_pesan) }}" 
                                           style="padding: 6px 12px; background: #43e97b; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                            ✅ Verifikasi
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
            {{ $pesanans->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <span style="font-size: 80px; display: block; margin-bottom: 20px;">📋</span>
            <h3 style="color: #222; margin-bottom: 10px; font-size: 22px;">Belum Ada Pemesanan</h3>
            <p style="color: #666; margin-bottom: 25px;">Belum ada pemesanan dari penyewa.</p>
        </div>
    @endif
</div>
@endsection
