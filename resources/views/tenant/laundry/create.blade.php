@extends('layouts.app')

@section('title', 'Pesan Laundry')

@section('content')
<div class="container" style="max-width: 700px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('laundry.orders.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">👕 Pesan Laundry</h1>
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

    @if ($laundryTypes->count() > 0)
        <form method="POST" action="{{ route('laundry.orders.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #333; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #970747; padding-bottom: 10px;">
                    1️⃣ Pilih Layanan Laundry
                </h3>

                <div style="display: grid; gap: 15px;">
                    @foreach ($laundryTypes as $l)
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; cursor: pointer; transition: all 0.3s;"
                               onmouseover="this.style.borderColor='#970747'; this.style.background='#fce4ec'"
                               onmouseout="this.style.borderColor='#e0e0e0'; this.style.background='white'">
                            <input type="radio" name="id_laundry_tipe" value="{{ $l->id_laundry_tipe }}" required
                                   style="width: 20px; height: 20px; margin-right: 15px; accent-color: #970747;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong style="font-size: 16px; color: #333;">{{ $l->nama_layanan }}</strong>
                                    <strong style="font-size: 18px; color: #970747;">{{ $l->formatted_harga_per_kg }}</strong>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
                <h3 style="color: #333; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #970747; padding-bottom: 10px;">
                    2️⃣ Upload Foto Pakaian
                </h3>
                <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                    📸 Foto pakaian Anda sebagai bukti kondisi awal sebelum dicuci.
                </p>
                
                <div class="form-group">
                    <input type="file" name="foto_awal" id="foto_awal" accept="image/*" required
                           style="width: 100%; padding: 12px; border: 2px dashed #970747; border-radius: 8px; font-size: 14px;"
                           onchange="previewImage(this)">
                    <small style="color: #666; font-size: 12px;">Max 2MB (JPG, PNG)</small>
                    
                    <div id="image-preview" style="margin-top: 15px; max-width: 300px; display: none;">
                        <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px; border: 2px solid #970747;">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="flex: 1; min-width: 200px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);">
                    📦 Buat Pesanan
                </button>
                <a href="{{ route('laundry.orders.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    @else
        <div style="background: white; border-radius: 12px; padding: 60px 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">😔</span>
            <h3 style="color: #970747; margin-bottom: 10px;">Tidak Ada Layanan Laundry Tersedia</h3>
            <p style="color: #666;">Maaf, saat ini belum ada layanan laundry yang tersedia untuk dipesan.</p>
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
