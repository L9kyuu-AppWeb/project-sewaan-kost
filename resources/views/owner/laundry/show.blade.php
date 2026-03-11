@extends('layouts.app')

@section('title', 'Detail Pesanan Laundry #' . $order->id_order_laundry)

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('owner.laundry.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Pesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Pesanan #{{ $order->id_order_laundry }}</h1>
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
            <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Layanan Laundry</p>
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0;">{{ $order->laundryType->nama_layanan ?? 'N/A' }}</h2>
                        @if ($order->laundryType)
                            <p style="font-size: 14px; opacity: 0.9; margin-top: 5px;">{{ $order->laundryType->formatted_harga_per_kg }}</p>
                        @endif
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Bayar</p>
                        <p style="font-size: 28px; font-weight: 700; margin: 0;">{{ $order->total_harga ? $order->formatted_total_harga : 'Menunggu berat' }}</p>
                        @php
                            $pembayaran = $order->pembayaran;
                        @endphp
                        @if ($pembayaran)
                            <p style="font-size: 12px; opacity: 0.9; margin-top: 5px;">
                                Payment: {{ $pembayaran->transaction_status ?? 'N/A' }}
                            </p>
                        @else
                            <p style="font-size: 12px; opacity: 0.9; margin-top: 5px; color: #ffc107;">
                                ⚠️ Belum ada pembayaran
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Weight & Estimation Status --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: {{ $order->berat_kg ? '#e8f5e9' : '#fff3cd' }}; border-radius: 12px; padding: 20px; border-left: 4px solid {{ $order->berat_kg ? '#43e97b' : '#ffc107' }};">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 10px;">⚖️ Status Penimbangan</h3>
                    @if ($order->berat_kg)
                        <p style="font-size: 24px; color: #2e7d32; font-weight: 700; margin: 0;">{{ $order->berat_kg }} kg</p>
                        <p style="font-size: 13px; color: #666; margin: 5px 0 0;">✓ Sudah ditimbang</p>
                    @else
                        <p style="font-size: 16px; color: #856404; margin: 0;">Belum ditimbang</p>
                        <p style="font-size: 13px; color: #856404; margin: 5px 0 0;">Owner harus menjemput dan menimbang</p>
                    @endif
                </div>

                <div style="background: {{ $order->tgl_selesai_estimasi ? '#e8f5e9' : '#fff3cd' }}; border-radius: 12px; padding: 20px; border-left: 4px solid {{ $order->tgl_selesai_estimasi ? '#43e97b' : '#ffc107' }};">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 10px;">📅 Estimasi Selesai</h3>
                    @if ($order->tgl_selesai_estimasi)
                        <p style="font-size: 18px; color: #2e7d32; font-weight: 600; margin: 0;">{{ $order->tgl_selesai_estimasi->format('d M Y') }}</p>
                        <p style="font-size: 13px; color: #666; margin: 5px 0 0;">✓ Sudah diisi</p>
                    @else
                        <p style="font-size: 16px; color: #856404; margin: 0;">Belum diisi</p>
                        <p style="font-size: 13px; color: #856404; margin: 5px 0 0;">Status: {{ $order->statusLabel }}</p>
                    @endif
                </div>
            </div>

            {{-- Photos --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Awal (Pakaian)</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Dari penyewa</p>
                    @if ($order->foto_awal)
                        <img src="{{ asset('storage/' . $order->foto_awal) }}" alt="Foto Awal" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #970747;">
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum ada foto</p>
                    @endif
                </div>

                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📸 Foto Selesai</h3>
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Upload setelah selesai</p>
                    @if ($order->foto_selesai)
                        <img src="{{ asset('storage/' . $order->foto_selesai) }}" alt="Foto Selesai" 
                             style="width: 100%; border-radius: 8px; border: 2px solid #43e97b;">
                        @if ($order->tgl_selesai_aktual)
                            <p style="font-size: 12px; color: #43e97b; margin-top: 10px; text-align: center;">
                                ✓ Diunggah {{ $order->tgl_selesai_aktual->format('d M Y, H:i') }}
                                @if ($order->isLate())
                                    <span style="color: #f5576c;">(Terlambat)</span>
                                @endif
                            </p>
                        @endif
                    @else
                        <p style="color: #999; text-align: center; padding: 40px 0;">Belum diunggah</p>
                    @endif
                </div>
            </div>

            {{-- Action Forms --}}
            @if ($order->status_laundry === 'menunggu_jemput')
                <div style="margin-top: 30px; padding: 25px; background: #fff3cd; border-radius: 12px; border-left: 4px solid #ffc107;">
                    <h3 style="font-size: 18px; color: #856404; margin-bottom: 15px;">⚖️ Input Berat & Harga</h3>
                    <p style="color: #856404; font-size: 13px; margin-bottom: 15px;">
                        Setelah menjemput pakaian, timbang dan input berat untuk menghitung harga.
                    </p>
                    
                    <form action="{{ route('owner.laundry.weigh', $order->id_order_laundry) }}" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; color: #333; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Berat (kg) *</label>
                                <input type="number" name="berat_kg" step="0.1" min="0.1" max="100" required
                                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                            </div>
                            <div>
                                <label style="display: block; color: #333; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Harga per Kg</label>
                                <input type="text" value="{{ $order->laundryType->formatted_harga_per_kg }}" readonly
                                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #f0f0f0;">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn" style="background: #970747;">
                            ⚖️ Update Berat & Harga
                        </button>
                    </form>
                </div>
            @endif

            @if ($order->canSetEstimationDate())
                <div style="margin-top: 30px; padding: 25px; background: #fff3cd; border-radius: 12px; border-left: 4px solid #ffc107;">
                    <h3 style="font-size: 18px; color: #856404; margin-bottom: 15px;">📅 Set Estimasi Selesai (WAJIB)</h3>
                    <p style="color: #856404; font-size: 13px; margin-bottom: 15px;">
                        <strong>PENTING:</strong> Anda wajib memberikan kepastian waktu selesai kepada penyewa.
                    </p>
                    
                    <form action="{{ route('owner.laundry.set-estimation', $order->id_order_laundry) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; color: #333; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Tanggal Perkiraan Selesai *</label>
                            <input type="date" name="tgl_selesai_estimasi" min="{{ date('Y-m-d') }}" required
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        
                        <button type="submit" class="btn" style="background: #970747;">
                            📅 Set Estimasi Selesai
                        </button>
                    </form>
                </div>
            @endif

            @if ($order->canUploadFinishedPhoto())
                <div style="margin-top: 30px; padding: 25px; background: #e8f5e9; border-radius: 12px; border-left: 4px solid #43e97b;">
                    <h3 style="font-size: 18px; color: #2e7d32; margin-bottom: 15px;">📸 Upload Foto Selesai</h3>
                    <p style="color: #666; font-size: 13px; margin-bottom: 15px;">
                        Upload foto laundry yang sudah selesai dan rapi sebagai bukti pengantaran.
                    </p>
                    
                    <form action="{{ route('owner.laundry.upload-finished', $order->id_order_laundry) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; color: #333; font-weight: 600; margin-bottom: 5px; font-size: 14px;">Foto Selesai *</label>
                            <input type="file" name="foto_selesai" accept="image/*" required
                                   style="width: 100%; padding: 12px; border: 2px dashed #43e97b; border-radius: 8px; font-size: 14px;"
                                   onchange="previewImage(this)">
                            <small style="color: #666; font-size: 12px;">Max 2MB (JPG, PNG)</small>
                            
                            <div id="image-preview" style="margin-top: 15px; max-width: 300px; display: none;">
                                <img id="preview" src="" alt="Preview" style="width: 100%; border-radius: 8px; border: 2px solid #43e97b;">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn" style="background: #43e97b;">
                            📸 Upload & Tandai Siap Antar
                        </button>
                    </form>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; gap: 10px; flex-wrap: wrap;">
                @if (in_array($order->status_laundry, ['menunggu_jemput', 'menunggu_bayar']))
                    <form action="{{ route('owner.laundry.cancel', $order->id_order_laundry) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if (!$order->berat_kg && $order->status_laundry === 'menunggu_jemput')
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ Silakan input berat setelah menjemput pakaian
                    </span>
                @elseif (!$order->tgl_selesai_estimasi && $order->status_laundry === 'sedang_dicuci')
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ WAJIB set estimasi selesai sebelum upload foto
                    </span>
                @elseif ($order->status_laundry === 'siap_antar')
                    <span style="color: #666; font-size: 14px; align-self: center;">
                        ℹ️ Menunggu konfirmasi dari penyewa
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
