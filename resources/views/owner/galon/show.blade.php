@extends('layouts.app')

@section('title', 'Detail Pesanan Galon #' . $order->id_order_galon)

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('owner.galon.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Pesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Pesanan #{{ $order->id_order_galon }}</h1>
                        <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: {{ $order->statusBadge }};">
                            {{ $order->statusLabel }}
                        </span>
                    </div>
                    <p style="color: #666; font-size: 16px;">🏢 {{ $order->kost->nama_kost }}</p>
                </div>
            </div>

            {{-- Pemesan Info --}}
            <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">👤 Informasi Pemesan</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #666; margin-bottom: 5px;">Nama</p>
                        <p style="font-size: 15px; color: #333; font-weight: 600; margin: 0;">{{ $order->penyewa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: #666; margin-bottom: 5px;">Email</p>
                        <p style="font-size: 15px; color: #333; margin: 0;">{{ $order->penyewa->email }}</p>
                    </div>
                    @if ($order->penyewa->no_hp)
                        <div>
                            <p style="font-size: 13px; color: #666; margin-bottom: 5px;">No HP</p>
                            <p style="font-size: 15px; color: #333; margin: 0;">{{ $order->penyewa->no_hp }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Info --}}
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Jenis Air</p>
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0;">{{ $order->galonType->nama_air ?? 'N/A' }}</h2>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Bayar</p>
                        <p style="font-size: 28px; font-weight: 700; margin: 0;">{{ $order->formatted_total_bayar }}</p>
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Galon Kosong</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Dari penyewa (bukti pengambilan)</p>
                    @if ($order->foto_kosong)
                        <img src="{{ asset('storage/' . $order->foto_kosong) }}" alt="Foto Galon Kosong" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #4facfe;">
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum ada foto</p>
                    @endif
                </div>

                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Galon Terisi</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Upload saat mengantar galon terisi</p>
                    @if ($order->foto_terisi)
                        <img src="{{ asset('storage/' . $order->foto_terisi) }}" alt="Foto Galon Terisi" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #43e97b;">
                        <p style="color: #43e97b; font-size: 13px; margin-top: 10px; text-align: center;">✓ Sudah diantar</p>
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum diunggah</p>
                    @endif
                </div>
            </div>

            {{-- Order Summary --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📅 Tanggal Pesan</p>
                    <p style="font-size: 16px; color: #333; font-weight: 600; margin: 0;">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📝 Order ID</p>
                    <p style="font-size: 14px; color: #333; font-weight: 600; margin: 0;">{{ $order->orderan_id }}</p>
                </div>
            </div>

            {{-- Action Form for Deliver --}}
            @if ($order->status_galon == 'diproses' && !$order->foto_terisi)
                <div style="margin-top: 30px; padding: 25px; background: #e8f5e9; border-radius: 12px; border-left: 4px solid #43e97b;">
                    <h3 style="font-size: 18px; color: #2e7d32; margin-bottom: 15px;">🚚 Upload Foto Galon Terisi</h3>
                    <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                        Upload foto galon yang sudah diisi dan ditaruh di depan kamar penyewa sebagai bukti pengantaran.
                    </p>
                    
                    <form action="{{ route('owner.galon.deliver', $order->id_order_galon) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" style="margin-bottom: 15px;">
                            <input type="file" name="foto_terisi" id="foto_terisi" accept="image/*" required
                                   style="width: 100%; padding: 12px; border: 2px dashed #43e97b; border-radius: 8px; font-size: 14px;"
                                   onchange="previewImage(this)">
                            <small style="color: #666; font-size: 12px;">Max 2MB (JPG, PNG)</small>
                            
                            <div id="image-preview" style="margin-top: 15px; max-width: 300px; display: none;">
                                <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px; border: 2px solid #43e97b;">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn" style="background: #43e97b;">
                            🚚 Tandai Sebagai Diantar
                        </button>
                    </form>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; gap: 10px; flex-wrap: wrap;">
                @if ($order->status_galon == 'menunggu_bayar')
                    <form action="{{ route('owner.galon.process', $order->id_order_galon) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai diproses?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #4facfe;">
                            ⚙️ Proses Pesanan
                        </button>
                    </form>
                    <form action="{{ route('owner.galon.cancel', $order->id_order_galon) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                @elseif ($order->status_galon == 'diproses' && !$order->foto_terisi)
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ Upload foto galon terisi untuk menandai sebagai diantar
                    </span>
                @else
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ Pesanan {{ $order->statusLabel }} - Tidak ada aksi lebih lanjut
                    </span>
                @endif
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; color: #999; font-size: 13px; flex-wrap: wrap; gap: 10px;">
                <span>Terakhir diupdate: {{ $order->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
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
