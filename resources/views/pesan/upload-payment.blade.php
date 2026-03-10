@extends('layouts.app')

@section('title', 'Upload Pembayaran - Sewa An Kost')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('pesan.show', $pesan->id_pesan) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Detail Pemesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 25px;">
            <span style="font-size: 50px; display: block; margin-bottom: 10px;">💳</span>
            <h1 style="font-size: 22px; color: #222; margin-bottom: 5px;">Upload Pembayaran</h1>
            <p style="color: #666; font-size: 14px;">Unggah bukti pembayaran Anda</p>
        </div>

        <!-- Booking Summary -->
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; margin-bottom: 25px; color: white;">
            <h3 style="font-size: 16px; margin-bottom: 10px;">Kamar {{ $pesan->kamar->nomor_kamar }}</h3>
            <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">{{ $pesan->kamar->kost->nama_kost }}</p>
            <p style="font-size: 20px; font-weight: 700; margin-top: 15px;">{{ $pesan->formattedTotalHarga }}</p>
        </div>

        <!-- Midtrans Payment Option -->
        <div style="background: #e7f3ff; padding: 20px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #2196F3;">
            <h3 style="font-size: 16px; color: #1565C0; margin-bottom: 10px; font-weight: 700;">⚡ Pembayaran Instan dengan Midtrans</h3>
            <p style="font-size: 13px; color: #1565C0; margin-bottom: 15px;">
                Bayar lebih cepat dan aman menggunakan berbagai metode pembayaran digital. Verifikasi otomatis!
            </p>
            <a href="{{ route('midtrans.pay', $pesan->id_pesan) }}" 
               style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
                🔒 Bayar dengan Midtrans
            </a>
        </div>

        <div class="divider" style="margin: 25px 0;">
            <span style="background: white; padding: 0 15px; color: #666; font-size: 12px;">ATAU</span>
        </div>

        <h3 style="font-size: 16px; color: #222; margin-bottom: 15px; font-weight: 700;">📤 Upload Bukti Pembayaran Manual</h3>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pesan.store-payment', $pesan->id_pesan) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="jenis_pembayaran">Jenis Pembayaran *</label>
                <select id="jenis_pembayaran" name="jenis_pembayaran" required onchange="toggleBankFields()">
                    <option value="">Pilih Jenis</option>
                    <option value="transfer_bank" {{ old('jenis_pembayaran') === 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="ewallet" {{ old('jenis_pembayaran') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="tunai" {{ old('jenis_pembayaran') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                </select>
            </div>

            <div id="bank_fields" style="display: none;">
                <div class="form-group">
                    <label for="nama_bank">Nama Bank *</label>
                    <input type="text" id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}" placeholder="Contoh: BCA, Mandiri, BNI">
                </div>

                <div class="form-group">
                    <label for="nomor_rekening">Nomor Rekening *</label>
                    <input type="text" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="Masukkan nomor rekening">
                </div>
            </div>

            <div class="form-group">
                <label for="jumlah_bayar">Jumlah Bayar (Rp) *</label>
                <input type="number" id="jumlah_bayar" name="jumlah_bayar" value="{{ old('jumlah_bayar', $pesan->total_harga) }}" required min="0" step="1000">
                <small style="color: #999; font-size: 11px;">Sesuai dengan total harga: {{ $pesan->formattedTotalHarga }}</small>
            </div>

            <div class="form-group">
                <label for="tanggal_bayar">Tanggal Transfer *</label>
                <input type="date" id="tanggal_bayar" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label for="bukti_pembayaran">Foto Bukti Pembayaran *</label>
                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required onchange="previewImage(this)">
                <small style="color: #999; font-size: 11px;">Max 2MB (JPG, PNG)</small>
                <div id="image-preview" style="margin-top: 10px; max-width: 300px; display: none;">
                    <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px;">
                </div>
            </div>

            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
                <p style="font-size: 13px; color: #856404; margin: 0;">
                    ⚠️ Pastikan bukti pembayaran jelas dan terbaca. Pembayaran akan diverifikasi oleh pemilik dalam 1-3 hari kerja.
                </p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="flex: 1;">
                    📤 Upload Pembayaran
                </button>
                <a href="{{ route('pesan.show', $pesan->id_pesan) }}" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleBankFields() {
        const jenis = document.getElementById('jenis_pembayaran').value;
        const bankFields = document.getElementById('bank_fields');
        const namaBank = document.getElementById('nama_bank');
        const nomorRekening = document.getElementById('nomor_rekening');

        if (jenis === 'transfer_bank') {
            bankFields.style.display = 'block';
            namaBank.required = true;
            nomorRekening.required = true;
        } else {
            bankFields.style.display = 'none';
            namaBank.required = false;
            nomorRekening.required = false;
        }
    }

    function previewImage(input) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Run on page load
    toggleBankFields();
</script>
@endsection
