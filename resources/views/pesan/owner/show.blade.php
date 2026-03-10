@extends('layouts.app')

@section('title', 'Detail Pemesanan - ' . $pesan->kamar->kost->nama_kost)

@section('content')
<div class="container-full" style="padding: 20px; max-width: 1000px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('pesan.owner.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Pemesanan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="font-size: 24px; color: #222; margin-bottom: 10px;">
                    Detail Pemesanan
                </h1>
                <p style="color: #666; font-size: 14px;">
                    ID Pemesanan: #{{ str_pad($pesan->id_pesan, 6, '0', STR_PAD_LEFT) }}
                </p>
            </div>
            <span style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; color: white; background: {{ $pesan->statusBadge }};">
                {{ $pesan->statusLabel }}
            </span>
        </div>

        <!-- Room Info -->
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; margin-bottom: 25px; color: white;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 50px;">🛏️</span>
                <div>
                    <h2 style="font-size: 20px; margin-bottom: 5px;">Kamar {{ $pesan->kamar->nomor_kamar }}</h2>
                    <p style="font-size: 14px; opacity: 0.9; margin: 0;">{{ $pesan->kamar->kost->nama_kost }}</p>
                </div>
            </div>
        </div>

        <!-- Booking Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">📅 Tanggal Mulai</p>
                <p style="font-size: 16px; color: #222; font-weight: 600;">{{ $pesan->tgl_mulai->format('d M Y') }}</p>
            </div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">📅 Tanggal Selesai</p>
                <p style="font-size: 16px; color: #222; font-weight: 600;">{{ $pesan->tgl_selesai->format('d M Y') }}</p>
            </div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">⏱️ Durasi</p>
                <p style="font-size: 16px; color: #222; font-weight: 600;">{{ $pesan->durasi_bulan }} bulan</p>
            </div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">💰 Total Harga</p>
                <p style="font-size: 18px; color: #970747; font-weight: 700;">{{ $pesan->formattedTotalHarga }}</p>
            </div>
        </div>

        <!-- Tenant Info -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <h3 style="font-size: 16px; color: #222; margin-bottom: 15px; font-weight: 700;">👤 Informasi Penyewa</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Nama</p>
                    <p style="font-size: 14px; color: #222; font-weight: 600;">{{ $pesan->penyewa->nama_lengkap }}</p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Email</p>
                    <p style="font-size: 14px; color: #222;">{{ $pesan->penyewa->email }}</p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">No. HP</p>
                    <p style="font-size: 14px; color: #222;">{{ $pesan->penyewa->no_hp }}</p>
                </div>
                @if ($pesan->penyewa->nik)
                    <div>
                        <p style="font-size: 12px; color: #666; margin-bottom: 3px;">NIK</p>
                        <p style="font-size: 14px; color: #222;">{{ $pesan->penyewa->nik }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if ($pesan->catatan)
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #ffc107;">
                <h3 style="font-size: 14px; color: #856404; margin-bottom: 8px; font-weight: 600;">📝 Catatan</h3>
                <p style="color: #856404; line-height: 1.6;">{{ $pesan->catatan }}</p>
            </div>
        @endif

        <!-- Payment Info -->
        @if ($pesan->payments->count() > 0)
            <div style="margin-top: 25px;">
                <h3 style="font-size: 18px; color: #222; margin-bottom: 15px; font-weight: 700;">💳 Riwayat Pembayaran</h3>
                @foreach ($pesan->payments as $payment)
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid {{ $payment->statusBadge }};">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                            <div style="flex: 1;">
                                <p style="font-size: 14px; color: #222; font-weight: 600; margin-bottom: 5px;">
                                    {{ $payment->jenisPembayaranLabel }} - {{ $payment->formattedJumlahBayar }}
                                </p>
                                <p style="font-size: 12px; color: #666; margin-bottom: 3px;">
                                    Tanggal: {{ $payment->tanggal_bayar->format('d M Y') }} | 
                                    Status: {{ $payment->statusLabel }}
                                </p>
                                @if ($payment->payment_type)
                                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">
                                        Payment Type: {{ $payment->payment_type }}
                                    </p>
                                @endif
                                @if ($payment->transaction_id)
                                    <p style="font-size: 11px; color: #666; font-family: monospace;">
                                        Transaction ID: {{ $payment->transaction_id }}
                                    </p>
                                @endif
                                @if ($payment->catatan_verifikasi)
                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                        Catatan: {{ $payment->catatan_verifikasi }}
                                    </p>
                                @endif
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                @if ($payment->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" 
                                       style="padding: 6px 12px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        👁️ Bukti
                                    </a>
                                @endif
                                @if ($payment->status_verifikasi === 'pending' && $pesan->status_pesan === 'proses_verifikasi')
                                    <a href="{{ route('pesan.owner.verify-payment', $pesan->id_pesan) }}" 
                                       style="padding: 6px 12px; background: #43e97b; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        ✅ Verifikasi
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Action Buttons -->
        <div style="display: flex; gap: 10px; margin-top: 30px; flex-wrap: wrap;">
            <a href="{{ route('pesan.owner.index') }}" class="btn" style="background: #6c757d;">
                ← Kembali
            </a>
            
            @if ($pesan->status_pesan === 'proses_verifikasi' && $pesan->latestPayment?->status_verifikasi === 'pending')
                <a href="{{ route('pesan.owner.verify-payment', $pesan->id_pesan) }}" class="btn" style="background: #43e97b;">
                    ✅ Verifikasi Pembayaran
                </a>
            @endif

            @if ($pesan->status_pesan === 'aktif')
                <form action="{{ route('pesan.owner.complete', $pesan->id_pesan) }}" method="POST" style="display: inline;" 
                      onsubmit="return confirm('Tandai pemesanan ini sebagai selesai?')">
                    @csrf
                    <button type="submit" class="btn" style="background: #6c757d;">
                        ✓ Tandai Selesai
                    </button>
                </form>
            @endif

            @if ($pesan->kamar->kost->pemilik->no_hp)
                <a href="https://wa.me/{{ $pesan->kamar->kost->pemilik->no_hp }}?text=Halo {{ $pesan->penyewa->nama_lengkap }}, mengenai pemesanan Kamar {{ $pesan->kamar->nomor_kamar }} di {{ $pesan->kamar->kost->nama_kost }}..." 
                   target="_blank"
                   class="btn" style="background: #25D366;">
                    💬 Hubungi Penyewa
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
