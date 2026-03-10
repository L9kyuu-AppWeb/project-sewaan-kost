@extends('layouts.app')

@section('title', 'Form Pemesanan - Sewa An Kost')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('kost-public.show', $kamar->kost->id_kost) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Detail Kost
        </a>
    </div>

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 25px;">
            <span style="font-size: 50px; display: block; margin-bottom: 10px;">📝</span>
            <h1 style="font-size: 22px; color: #222; margin-bottom: 5px;">Form Pemesanan</h1>
            <p style="color: #666; font-size: 14px;">Lengkapi data untuk memesan kamar</p>
        </div>

        <!-- Room Summary -->
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; margin-bottom: 25px; color: white;">
            <h3 style="font-size: 16px; margin-bottom: 10px;">Kamar {{ $kamar->nomor_kamar }}</h3>
            <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">{{ $kamar->kost->nama_kost }}</p>
            <p style="font-size: 18px; font-weight: 700; margin-top: 15px;">{{ $kamar->formattedPrice }}<span style="font-size: 12px; font-weight: 400; opacity: 0.8;">/bulan</span></p>
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

        <form method="POST" action="{{ route('pesan.store') }}">
            @csrf

            <input type="hidden" name="id_kamar" value="{{ $kamar->id_kamar }}">

            <div class="form-group">
                <label for="tgl_mulai">Tanggal Mulai Sewa *</label>
                <input type="date" id="tgl_mulai" name="tgl_mulai" value="{{ old('tgl_mulai', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+15 days')) }}" onchange="calculateEndDate()">
                <small style="color: #999; font-size: 11px;">📅 Maksimal 15 hari dari hari ini (toleransi menempati)</small>
            </div>

            <div class="form-group">
                <label for="durasi_bulan">Durasi Sewa (Bulan) *</label>
                <select id="durasi_bulan" name="durasi_bulan" required onchange="calculateTotal()">
                    <option value="">Pilih Durasi</option>
                    @for ($i = 1; $i <= 24; $i++)
                        <option value="{{ $i }}" {{ old('durasi_bulan') == $i ? 'selected' : '' }}>
                            {{ $i }} bulan
                        </option>
                    @endfor
                </select>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666; font-size: 14px;">Harga per bulan:</span>
                    <span style="color: #222; font-weight: 600;">{{ $kamar->formattedPrice }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666; font-size: 14px;">Durasi:</span>
                    <span style="color: #222; font-weight: 600;"><span id="durasi_display">-</span> bulan</span>
                </div>
                <div style="border-top: 2px solid #ddd; padding-top: 10px; margin-top: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #222; font-weight: 700; font-size: 16px;">Total Harga:</span>
                        <span style="color: #970747; font-weight: 700; font-size: 20px;" id="total_display">-</span>
                    </div>
                </div>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #ddd;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #666; font-size: 14px;">Tanggal Selesai:</span>
                        <span style="color: #222; font-weight: 600;" id="end_date_display">-</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan Tambahan (Opsional)</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Contoh: Saya akan mulai menempati tanggal 1, mohon kamar dibersihkan sebelumnya.">{{ old('catatan') }}</textarea>
                <small style="color: #999; font-size: 11px;">Maksimal 500 karakter</small>
            </div>

            <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196F3;">
                <p style="font-size: 13px; color: #1565C0; margin: 0;">
                    ℹ️ Setelah pemesanan, status kamar akan berubah menjadi "Dipesan". Silakan lakukan pembayaran dalam waktu 1x24 jam untuk melanjutkan proses verifikasi.
                </p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="flex: 1;">
                    📝 Pesan Sekarang
                </button>
                <a href="{{ route('kost-public.show', $kamar->kost->id_kost) }}" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const hargaPerBulan = {{ $kamar->harga_per_bulan }};

    function calculateEndDate() {
        const tglMulai = document.getElementById('tgl_mulai').value;
        const durasi = document.getElementById('durasi_bulan').value;
        
        if (tglMulai && durasi) {
            const startDate = new Date(tglMulai);
            startDate.setMonth(startDate.getMonth() + parseInt(durasi));
            const endDate = startDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('end_date_display').textContent = endDate;
        }
    }

    function calculateTotal() {
        const durasi = document.getElementById('durasi_bulan').value;
        
        if (durasi) {
            const total = hargaPerBulan * parseInt(durasi);
            document.getElementById('durasi_display').textContent = durasi;
            document.getElementById('total_display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            calculateEndDate();
        }
    }

    // Run on page load
    calculateEndDate();
</script>
@endsection
