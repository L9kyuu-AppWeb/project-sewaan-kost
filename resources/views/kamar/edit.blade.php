@extends('layouts.app')

@section('title', 'Edit Kamar - Sewa An Kost')

@section('content')
<div class="container">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('kamar.index', ['kost_id' => $kamar->id_kost]) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Kamar
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">✏️ Edit Kamar</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Update informasi kamar</p>
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

    <form method="POST" action="{{ route('kamar.update', $kamar->id_kamar) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="id_kost">Pilih Kost *</label>
                    <select id="id_kost" name="id_kost" required>
                        @foreach ($kosts as $kost)
                            <option value="{{ $kost->id_kost }}" {{ old('id_kost', $kamar->id_kost) == $kost->id_kost ? 'selected' : '' }}>
                                {{ $kost->nama_kost }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="nomor_kamar">Nomor Kamar *</label>
                    <input type="text" id="nomor_kamar" name="nomor_kamar" value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}" required placeholder="Contoh: A-01">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="lantai">Lantai</label>
                    <input type="number" id="lantai" name="lantai" value="{{ old('lantai', $kamar->lantai) }}" min="0" placeholder="0">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="harga_per_bulan">Harga Sewa per Bulan (Rp) *</label>
                    <input type="number" id="harga_per_bulan" name="harga_per_bulan" value="{{ old('harga_per_bulan', $kamar->harga_per_bulan) }}" required min="0" placeholder="1000000">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="status_kamar">Status Kamar *</label>
                    <select id="status_kamar" name="status_kamar" required>
                        <option value="tersedia" {{ old('status_kamar', $kamar->status_kamar) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="dipesan" {{ old('status_kamar', $kamar->status_kamar) === 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                        <option value="terisi" {{ old('status_kamar', $kamar->status_kamar) === 'terisi' ? 'selected' : '' }}>Terisi</option>
                    </select>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="ukuran_kamar">Ukuran Kamar</label>
                    <input type="text" id="ukuran_kamar" name="ukuran_kamar" value="{{ old('ukuran_kamar', $kamar->ukuran_kamar) }}" placeholder="Contoh: 3x4 meter">
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="fasilitas_kamar">Fasilitas Kamar</label>
                    <textarea id="fasilitas_kamar" name="fasilitas_kamar" placeholder="Contoh: AC, Kamar mandi dalam, Kasur, Lemari">{{ old('fasilitas_kamar', $kamar->fasilitas_kamar) }}</textarea>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="foto_kamar">Foto Kamar</label>
                    @if ($kamar->foto_kamar)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $kamar->foto_kamar) }}" alt="Foto saat ini" style="max-width: 300px; border-radius: 8px;">
                            <p style="font-size: 12px; color: rgba(255,255,255,0.8); margin-top: 5px;">Foto saat ini</p>
                        </div>
                    @endif
                    <input type="file" id="foto_kamar" name="foto_kamar" accept="image/*" onchange="previewImage(this)">
                    <small style="color: rgba(255,255,255,0.7); font-size: 11px;">Max 2MB (JPG, PNG) - Kosongkan jika tidak ingin mengganti</small>
                    <div id="image-preview" style="margin-top: 10px; max-width: 300px; display: none;">
                        <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap;">
            <button type="submit" class="btn" style="flex: 1; min-width: 200px;">
                💾 Update Data Kamar
            </button>
            <a href="{{ route('kamar.index', ['kost_id' => $kamar->id_kost]) }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
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
</script>
@endsection
