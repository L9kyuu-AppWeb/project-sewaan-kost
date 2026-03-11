@extends('layouts.app')

@section('title', 'Pesan Galon')

@section('content')
<div class="container" style="max-width: 700px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('galon.orders.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">💧 Pesan Galon</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">{{ $kost->nama_kost }}</p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <strong style="color: #721c24;">⚠️ Terdapat kesalahan:</strong>
            <ul style="margin: 10px 0 0 20px; color: #721c24;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($galonTypes->count() > 0)
        <form method="POST" action="{{ route('galon.orders.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #333; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #4facfe; padding-bottom: 10px;">
                    1️⃣ Pilih Jenis Air
                </h3>

                <div style="display: grid; gap: 15px;">
                    @foreach ($galonTypes as $g)
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='#4facfe'; this.style.background='#f0f9ff'"
                               onmouseout="this.style.borderColor='#e0e0e0'; this.style.background='white'">
                            <input type="radio" name="id_galon_tipe" value="{{ $g->id_galon_tipe }}" required
                                   style="width: 20px; height: 20px; margin-right: 15px; accent-color: #4facfe;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong style="font-size: 16px; color: #333;">{{ $g->nama_air }}</strong>
                                    <strong style="font-size: 18px; color: #4facfe;">{{ $g->formatted_harga }}</strong>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
                <h3 style="color: #333; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #4facfe; padding-bottom: 10px;">
                    2️⃣ Upload Foto Galon Kosong
                </h3>
                <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                    📸 Foto galon kosong harus diletakkan di depan kamar Anda sebagai bukti pengambilan.
                </p>
                
                <div class="form-group">
                    <input type="file" name="foto_kosong" id="foto_kosong" accept="image/*" required
                           style="width: 100%; padding: 12px; border: 2px dashed #4facfe; border-radius: 8px; font-size: 14px;"
                           onchange="previewImage(this)">
                    <small style="color: #666; font-size: 12px;">Max 2MB (JPG, PNG)</small>
                    
                    <div id="image-preview" style="margin-top: 15px; max-width: 300px; display: none;">
                        <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px; border: 2px solid #4facfe;">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="flex: 1; min-width: 200px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    💧 Buat Pesanan
                </button>
                <a href="{{ route('galon.orders.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    @else
        <div style="background: white; border-radius: 12px; padding: 60px 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">😔</span>
            <h3 style="color: #4facfe; margin-bottom: 10px;">Tidak Ada Jenis Air Tersedia</h3>
            <p style="color: #666;">Maaf, saat ini belum ada jenis air galon yang tersedia untuk dipesan.</p>
        </div>
    @endif
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
