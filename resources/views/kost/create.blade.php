@extends('layouts.app')

@section('title', 'Tambah Kost - Sewa An Kost')

@section('content')
<div class="container">
    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">➕ Tambah Kost Baru</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Isi form di bawah untuk menambahkan properti kost</p>
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

    <form method="POST" action="{{ route('kost.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="nama_kost">Nama Kost *</label>
                    <input type="text" id="nama_kost" name="nama_kost" value="{{ old('nama_kost') }}" required placeholder="Contoh: Kost Mawar Biru">
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap *</label>
                    <textarea id="alamat" name="alamat" required placeholder="Alamat fisik lengkap kost">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" placeholder="Penjelasan singkat mengenai lingkungan kost">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="fasilitas_umum">Fasilitas Umum</label>
                    <textarea id="fasilitas_umum" name="fasilitas_umum" placeholder="Contoh: WiFi, Parkir Motor, Kamar Mandi Dalam, AC">{{ old('fasilitas_umum') }}</textarea>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="peraturan">Peraturan Kost</label>
                    <textarea id="peraturan" name="peraturan" placeholder="Contoh: Jam malam 22:00, Dilarang merokok di dalam kamar">{{ old('peraturan') }}</textarea>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="latitude">Latitude (Koordinat)</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="-6.20876345" step="any">
                    <small style="color: #999; font-size: 11px;">Untuk integrasi Google Maps (opsional)</small>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="longitude">Longitude (Koordinat)</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="106.84559900" step="any">
                    <small style="color: #999; font-size: 11px;">Untuk integrasi Google Maps (opsional)</small>
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="form-group">
                    <label for="foto_kost">Foto Kost (Tampak Depan)</label>
                    <input type="file" id="foto_kost" name="foto_kost" accept="image/*" onchange="previewImage(this)">
                    <small style="color: #999; font-size: 11px;">Max 2MB (JPG, PNG)</small>
                    <div id="image-preview" style="margin-top: 10px; max-width: 300px; display: none;">
                        <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap;">
            <button type="submit" class="btn" style="flex: 1; min-width: 200px;">
                💾 Simpan Data Kost
            </button>
            <a href="{{ route('kost.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
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
