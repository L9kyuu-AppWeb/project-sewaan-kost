@extends('layouts.app')

@section('title', 'Tambah Menu Makanan - Sewa An Kost')

@section('content')
<div class="container">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('makanan.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Makanan
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">➕ Tambah Menu Baru</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Isi form di bawah untuk menambahkan menu makanan</p>
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

    <form method="POST" action="{{ route('makanan.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="id_kost">Pilih Kost *</label>
                    <select id="id_kost" name="id_kost" required>
                        <option value="">-- Pilih Kost --</option>
                        @foreach ($kosts as $kost)
                            <option value="{{ $kost->id_kost }}" {{ old('id_kost') == $kost->id_kost ? 'selected' : '' }}>
                                {{ $kost->nama_kost }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="nama_makanan">Nama Makanan *</label>
                    <input type="text" id="nama_makanan" name="nama_makanan" value="{{ old('nama_makanan') }}" required placeholder="Contoh: Nasi Goreng Spesial">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="harga">Harga per Porsi (Rp) *</label>
                    <input type="number" id="harga" name="harga" value="{{ old('harga') }}" required min="0" placeholder="15000">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="stok">Stok Awal (Porsi) *</label>
                    <input type="number" id="stok" name="stok" value="{{ old('stok', 0) }}" required min="0" placeholder="10">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="is_available">Status Ketersediaan *</label>
                    <select id="is_available" name="is_available" required>
                        <option value="1" {{ old('is_available', true) ? 'selected' : '' }}>Tersedia (Ditampilkan)</option>
                        <option value="0" {{ old('is_available') === false ? 'selected' : '' }}>Tidak Tersedia (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="foto_makanan">Foto Makanan</label>
                    <input type="file" id="foto_makanan" name="foto_makanan" accept="image/*" onchange="previewImage(this)">
                    <small style="color: rgba(255,255,255,0.7); font-size: 11px;">Max 2MB (JPG, PNG)</small>
                    <div id="image-preview" style="margin-top: 10px; max-width: 300px; display: none;">
                        <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap;">
            <button type="submit" class="btn" style="flex: 1; min-width: 200px;">
                💾 Simpan Data Menu
            </button>
            <a href="{{ route('makanan.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
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
