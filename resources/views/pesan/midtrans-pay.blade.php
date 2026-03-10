@extends('layouts.app')

@section('title', 'Pembayaran - ' . $pesan->kamar->kost->nama_kost)

@section('content')
<div class="container" style="max-width: 700px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('pesan.show', $pesan->id_pesan) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Detail Pemesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 60px; display: block; margin-bottom: 15px;">💳</span>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 10px;">Pembayaran Kamar</h1>
            <p style="color: #666; font-size: 14px;">Selesaikan pembayaran Anda dengan aman</p>
        </div>

        <!-- Debug Info -->
        @if(app()->environment('local'))
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 11px; font-family: monospace;">
                <p style="color: #666; margin-bottom: 5px;"><strong>Debug Info:</strong></p>
                <p style="color: #666; margin-bottom: 3px;">Snap Token: {{ $snapToken ?: 'NULL' }}</p>
                <p style="color: #666; margin-bottom: 3px;">Order ID: {{ $pembayaran->order_id }}</p>
                <p style="color: #666; margin-bottom: 3px;">Amount: {{ $pembayaran->jumlah_bayar }}</p>
                <p style="color: #666; margin-bottom: 3px;">Server Key: {{ substr(config('midtrans.server_key'), 0, 20) }}...</p>
                <p style="color: #666;">Client Key: {{ substr(config('midtrans.client_key'), 0, 20) }}...</p>
            </div>
        @endif

        <!-- Order Summary -->
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 25px; border-radius: 12px; margin-bottom: 25px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div>
                    <h2 style="font-size: 18px; margin-bottom: 5px;">{{ $pesan->kamar->kost->nama_kost }}</h2>
                    <p style="font-size: 14px; opacity: 0.9;">Kamar {{ $pesan->kamar->nomor_kamar }}</p>
                </div>
                <div style="text-align: right;">
                    <p style="font-size: 24px; font-weight: 700;">{{ $pesan->formattedTotalHarga }}</p>
                    <p style="font-size: 12px; opacity: 0.8;">{{ $pesan->durasi_bulan }} bulan</p>
                </div>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.3); padding-top: 15px; margin-top: 15px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <p style="font-size: 12px; opacity: 0.8; margin-bottom: 3px;">Penyewa</p>
                        <p style="font-size: 14px; font-weight: 600;">{{ $pesan->penyewa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; opacity: 0.8; margin-bottom: 3px;">Order ID</p>
                        <p style="font-size: 14px; font-weight: 600; font-family: monospace;">{{ $pembayaran->order_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Info -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <h3 style="font-size: 16px; color: #222; margin-bottom: 15px; font-weight: 700;">💳 Metode Pembayaran Tersedia</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div style="background: white; padding: 12px; border-radius: 6px;">
                    <p style="font-size: 13px; font-weight: 600; color: #222; margin-bottom: 5px;">🏦 Transfer Bank</p>
                    <p style="font-size: 11px; color: #666;">BCA, Mandiri, BNI, BRI</p>
                </div>
                <div style="background: white; padding: 12px; border-radius: 6px;">
                    <p style="font-size: 13px; font-weight: 600; color: #222; margin-bottom: 5px;">📱 E-Wallet</p>
                    <p style="font-size: 11px; color: #666;">GoPay, OVO, Dana, LinkAja</p>
                </div>
                <div style="background: white; padding: 12px; border-radius: 6px;">
                    <p style="font-size: 13px; font-weight: 600; color: #222; margin-bottom: 5px;">💳 Kartu Kredit</p>
                    <p style="font-size: 11px; color: #666;">Visa, Mastercard</p>
                </div>
                <div style="background: white; padding: 12px; border-radius: 6px;">
                    <p style="font-size: 13px; font-weight: 600; color: #222; margin-bottom: 5px;">🏪 Minimarket</p>
                    <p style="font-size: 11px; color: #666;">Alfamart, Indomaret</p>
                </div>
            </div>
        </div>

        <!-- Important Info -->
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #ffc107;">
            <p style="font-size: 13px; color: #856404; margin: 0;">
                ⚠️ <strong>Penting:</strong> Pembayaran harus diselesaikan dalam waktu 24 jam. Jika tidak, pemesanan akan otomatis dibatalkan dan kamar akan tersedia kembali.
            </p>
        </div>

        <!-- Pay Button -->
        <button id="pay-button" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 20px rgba(151,7,71,0.4)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            🔒 Bayar Sekarang
        </button>

        @if(config('midtrans.server_key') === 'SB-Mid-server-xxx' || config('midtrans.server_key') === 'SB-Mid-server-test')
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ffc107;">
                <p style="font-size: 13px; color: #856404; margin: 0;">
                    ⚠️ <strong>Perhatian:</strong> Midtrans Server Key belum dikonfigurasi dengan benar. 
                    Silakan update <code>.env</code> dengan credentials dari Midtrans Dashboard.
                </p>
                <p style="font-size: 11px; color: #856404; margin-top: 5px;">
                    Current key: {{ config('midtrans.server_key') }}
                </p>
            </div>
        @endif

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('pesan.show', $pesan->id_pesan) }}" style="color: #666; text-decoration: none; font-size: 14px;">
                Batal dan kembali ke detail pemesanan
            </a>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    // Debug: Log snap token
    console.log('Snap Token:', '{{ $snapToken }}');
    console.log('Client Key:', '{{ config('midtrans.client_key') }}');
    
    document.getElementById('pay-button').onclick = function () {
        // Snap Token from server
        const snapToken = '{{ $snapToken }}';
        
        console.log('Payment button clicked, token:', snapToken);
        
        if (!snapToken || snapToken === '') {
            alert('Error: Snap token tidak valid. Silakan hubungi admin.');
            return;
        }
        
        // Snap popup
        snap.pay(snapToken, {
            onSuccess: function(result) {
                // Payment success
                console.log('Payment success:', result);
                window.location.href = '{{ route("midtrans.success", $pesan->id_pesan) }}';
            },
            onPending: function(result) {
                // Payment pending (for some payment methods)
                console.log('Payment pending:', result);
                window.location.href = '{{ route("pesan.show", $pesan->id_pesan) }}';
            },
            onError: function(result) {
                // Payment error
                console.log('Payment error:', result);
                window.location.href = '{{ route("midtrans.failed", $pesan->id_pesan) }}';
            },
            onClose: function() {
                // User closed popup without paying
                console.log('User closed payment popup');
                alert('Anda menutup popup pembayaran. Silakan klik tombol "Bayar Sekarang" untuk mencoba lagi.');
            }
        });
    };
</script>
@endsection
