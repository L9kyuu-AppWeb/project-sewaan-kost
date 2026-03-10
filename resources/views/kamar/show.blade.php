@extends('layouts.app')

@section('title', $kamar->nomor_kamar . ' - Detail Kamar')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('kamar.index', ['kost_id' => $kamar->id_kost]) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Kamar
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="height: 350px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center;">
            @if ($kamar->foto_kamar)
                <img src="{{ asset('storage/' . $kamar->foto_kamar) }}" alt="{{ $kamar->nomor_kamar }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span style="font-size: 100px; color: rgba(255,255,255,0.5);">🛏️</span>
            @endif
        </div>

        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Kamar {{ $kamar->nomor_kamar }}</h1>
                        <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: {{ $kamar->statusBadge }};">
                            {{ $kamar->statusLabel }}
                        </span>
                    </div>
                    <p style="color: #666; font-size: 16px;">🏢 {{ $kamar->kost->nama_kost }}</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('kamar.edit', $kamar->id_kamar) }}" class="btn" style="width: auto; padding: 10px 20px; background: #43e97b;">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('kamar.destroy', $kamar->id_kamar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="width: auto; padding: 10px 20px; background: #f5576c;">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 30px;">
                <div style="background: #f8f9fa; padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 16px; color: #970747; margin-bottom: 15px; font-weight: 700;">💰 Informasi Harga</h3>
                    <p style="font-size: 28px; color: #970747; font-weight: 700; margin-bottom: 5px;">{{ $kamar->formattedPrice }}</p>
                    <p style="font-size: 13px; color: #666;">per bulan</p>
                </div>

                <div style="background: #f8f9fa; padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 16px; color: #970747; margin-bottom: 15px; font-weight: 700;">📍 Lokasi Kamar</h3>
                    <p style="font-size: 16px; color: #333; margin-bottom: 5px;">
                        <span style="color: #666;">Lantai:</span> {{ $kamar->lantai ?? '-' }}
                    </p>
                    <p style="font-size: 16px; color: #333;">
                        <span style="color: #666;">Ukuran:</span> {{ $kamar->ukuran_kamar ?? '-' }}
                    </p>
                </div>

                <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; grid-column: span 1;">
                    <h3 style="font-size: 16px; color: #970747; margin-bottom: 15px; font-weight: 700;">🏷️ Fasilitas</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kamar->fasilitas_kamar ?? 'Tidak ada fasilitas yang disebutkan.' }}</p>
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; color: #999; font-size: 13px; flex-wrap: wrap; gap: 10px;">
                <span>Dibuat: {{ $kamar->created_at->format('d M Y, H:i') }}</span>
                <span>Terakhir diupdate: {{ $kamar->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Back to Kost Link -->
    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('kost.show', $kamar->id_kost) }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            🏢 Lihat Detail Kost →
        </a>
    </div>
</div>
@endsection
