@extends('layouts.app')

@section('title', $kost->nama_kost . ' - Detail Kost')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('kost.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Kost
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
            @if ($kost->foto_kost)
                <img src="{{ asset('storage/' . $kost->foto_kost) }}" alt="{{ $kost->nama_kost }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span style="font-size: 80px; color: rgba(255,255,255,0.5);">🏢</span>
            @endif
        </div>

        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-size: 28px; color: #333; margin-bottom: 10px;">{{ $kost->nama_kost }}</h1>
                    <p style="color: #666; font-size: 16px;">📍 {{ $kost->alamat }}</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('kost.edit', $kost->id_kost) }}" class="btn" style="width: auto; padding: 10px 20px; background: #43e97b;">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('kost.destroy', $kost->id_kost) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kost ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="width: auto; padding: 10px 20px; background: #f5576c;">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; color: #970747; margin-bottom: 15px; border-bottom: 2px solid #fce4ec; padding-bottom: 8px;">📋 Deskripsi</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kost->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                    <h3 style="font-size: 18px; color: #970747; margin: 25px 0 15px; border-bottom: 2px solid #fce4ec; padding-bottom: 8px;">🏷️ Fasilitas Umum</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kost->fasilitas_umum ?? 'Tidak ada fasilitas yang disebutkan.' }}</p>
                </div>

                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; color: #970747; margin-bottom: 15px; border-bottom: 2px solid #fce4ec; padding-bottom: 8px;">📜 Peraturan</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kost->peraturan ?? 'Tidak ada peraturan yang disebutkan.' }}</p>

                    @if ($kost->latitude && $kost->longitude)
                        <h3 style="font-size: 18px; color: #970747; margin: 25px 0 15px; border-bottom: 2px solid #fce4ec; padding-bottom: 8px;">📍 Lokasi</h3>
                        <p style="color: #666; margin-bottom: 10px;">
                            Latitude: {{ $kost->latitude }}<br>
                            Longitude: {{ $kost->longitude }}
                        </p>
                        <a href="https://www.google.com/maps?q={{ $kost->latitude }},{{ $kost->longitude }}" target="_blank" 
                           style="display: inline-block; padding: 8px 16px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                            🗺️ Buka di Google Maps
                        </a>
                    @endif
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; color: #999; font-size: 13px; flex-wrap: wrap; gap: 10px;">
                <span>Dibuat: {{ $kost->created_at->format('d M Y, H:i') }}</span>
                <span>Terakhir diupdate: {{ $kost->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <!-- Rooms Section -->
        <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #fce4ec;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 22px; color: #970747; margin: 0;">🛏️ Daftar Kamar ({{ $kost->rooms->count() }})</h2>
                <a href="{{ route('kamar.create') }}" class="btn" style="width: auto; padding: 10px 20px; font-size: 13px;">
                    ➕ Tambah Kamar
                </a>
            </div>

            @if ($kost->rooms->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    @foreach ($kost->rooms as $room)
                        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 1px solid #eee;">
                            <div style="height: 150px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center;">
                                @if ($room->foto_kamar)
                                    <img src="{{ asset('storage/' . $room->foto_kamar) }}" alt="{{ $room->nomor_kamar }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 50px; color: rgba(255,255,255,0.5);">🛏️</span>
                                @endif
                            </div>
                            <div style="padding: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                    <h3 style="font-size: 16px; color: #333; margin: 0;">Kamar {{ $room->nomor_kamar }}</h3>
                                    <span style="padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; color: white; background: {{ $room->statusBadge }};">
                                        {{ $room->statusLabel }}
                                    </span>
                                </div>
                                <p style="font-size: 18px; color: #970747; font-weight: 700; margin: 10px 0;">{{ $room->formattedPrice }}<span style="font-size: 12px; color: #666; font-weight: 400;">/bulan</span></p>
                                <div style="display: flex; gap: 10px; margin-top: 15px;">
                                    <a href="{{ route('kamar.show', $room->id_kamar) }}" style="flex: 1; padding: 8px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center;">
                                        👁️ Detail
                                    </a>
                                    <a href="{{ route('kamar.edit', $room->id_kamar) }}" style="flex: 1; padding: 8px; background: #c41e6a; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center;">
                                        ✏️ Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 12px;">
                    <span style="font-size: 50px; display: block; margin-bottom: 15px;">🛏️</span>
                    <p style="color: #666; margin-bottom: 15px;">Belum ada kamar di kost ini.</p>
                    <a href="{{ route('kamar.create') }}" class="btn" style="width: auto; padding: 10px 20px; font-size: 13px;">
                        ➕ Tambah Kamar Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
