@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - ' . $pesan->kamar->kost->nama_kost)

@section('content')
<div class="container" style="max-width: 700px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('pesan.owner.show', $pesan->id_pesan) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Detail Pemesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 25px;">
            <span style="font-size: 60px; display: block; margin-bottom: 15px;">✅</span>
            <h1 style="font-size: 22px; color: #222; margin-bottom: 5px;">Verifikasi Pembayaran</h1>
            <p style="color: #666; font-size: 14px;">Periksa dan verifikasi pembayaran penyewa</p>
        </div>

        <!-- Order Summary -->
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; margin-bottom: 25px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <h2 style="font-size: 18px; margin-bottom: 5px;">Kamar {{ $pesan->kamar->nomor_kamar }}</h2>
                    <p style="font-size: 14px; opacity: 0.9; margin: 0;">{{ $pesan->kamar->kost->nama_kost }}</p>
                </div>
                <div style="text-align: right;">
                    <p style="font-size: 20px; font-weight: 700;">{{ $pesan->formattedTotalHarga }}</p>
                    <p style="font-size: 12px; opacity: 0.8;">{{ $pesan->durasi_bulan }} bulan</p>
                </div>
            </div>
        </div>

        <!-- Penyewa Info -->
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-size: 13px; color: #666; margin-bottom: 8px;"><strong>Penyewa:</strong></p>
            <p style="font-size: 15px; color: #222; font-weight: 600; margin: 0;">{{ $pesan->penyewa->nama_lengkap }}</p>
            <p style="font-size: 13px; color: #666; margin: 3px 0 0;">{{ $pesan->penyewa->email }} • {{ $pesan->penyewa->no_hp }}</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Payment Options -->
        @foreach ($pendingPayments as $payment)
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid {{ $payment->statusBadge }};">
                <h3 style="font-size: 16px; color: #222; margin-bottom: 15px; font-weight: 700;">
                    💳 Detail Pembayaran
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Jenis Pembayaran</p>
                        <p style="font-size: 14px; color: #222; font-weight: 600;">{{ $payment->jenisPembayaranLabel }}</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Jumlah Bayar</p>
                        <p style="font-size: 16px; color: #970747; font-weight: 700;">{{ $payment->formattedJumlahBayar }}</p>
                    </div>
                    @if ($payment->nama_bank)
                        <div>
                            <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Bank</p>
                            <p style="font-size: 14px; color: #222;">{{ $payment->nama_bank }}</p>
                        </div>
                    @endif
                    @if ($payment->nomor_rekening)
                        <div>
                            <p style="font-size: 12px; color: #666; margin-bottom: 3px;">No. Rekening</p>
                            <p style="font-size: 14px; color: #222;">{{ $payment->nomor_rekening }}</p>
                        </div>
                    @endif
                    <div>
                        <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Tanggal Transfer</p>
                        <p style="font-size: 14px; color: #222;">{{ $payment->tanggal_bayar->format('d M Y') }}</p>
                    </div>
                </div>

                @if ($payment->bukti_pembayaran)
                    <div style="margin-bottom: 15px;">
                        <p style="font-size: 12px; color: #666; margin-bottom: 8px;">Bukti Pembayaran:</p>
                        <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" alt="Bukti Pembayaran" 
                             style="max-width: 100%; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                @endif

                @if ($payment->transaction_id)
                    <div style="background: #e7f3ff; padding: 12px; border-radius: 6px; margin-bottom: 15px;">
                        <p style="font-size: 11px; color: #1565C0; margin: 0; font-family: monospace;">
                            <strong>Midtrans Transaction ID:</strong> {{ $payment->transaction_id }}
                        </p>
                        @if ($payment->payment_type)
                            <p style="font-size: 11px; color: #1565C0; margin: 5px 0 0; font-family: monospace;">
                                <strong>Payment Type:</strong> {{ $payment->payment_type }}
                            </p>
                        @endif
                        @if ($payment->transaction_status)
                            <p style="font-size: 11px; color: #1565C0; margin: 5px 0 0; font-family: monospace;">
                                <strong>Transaction Status:</strong> {{ $payment->transaction_status }}
                            </p>
                        @endif
                    </div>
                @endif

                <div style="background: #fff3cd; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                    <p style="font-size: 13px; color: #856404; margin: 0;">
                        ℹ️ <strong>Info:</strong> Pembayaran melalui Midtrans akan diverifikasi otomatis oleh sistem. 
                        Status pembayaran akan berubah menjadi <strong>"Settlement (Berhasil)"</strong> setelah pembayaran selesai.
                    </p>
                </div>
            </div>
        @endforeach

        <!-- Info Box -->
        <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; border-left: 4px solid #2196F3; margin-top: 20px;">
            <p style="font-size: 13px; color: #1565C0; margin: 0;">
                ℹ️ <strong>Catatan:</strong> Pembayaran melalui Midtrans akan diverifikasi otomatis oleh sistem. 
                Setelah pembayaran berhasil (status: settlement), status pemesanan akan berubah menjadi "Aktif" dan status kamar akan berubah menjadi "Terisi".
            </p>
        </div>
    </div>
</div>
@endsection
