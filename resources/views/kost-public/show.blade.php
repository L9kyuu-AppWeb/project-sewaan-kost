@extends('layouts.app')

@section('title', $kost->nama_kost . ' - Detail Kost')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <!-- Back Button -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('kost-public.index') }}" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Kost
        </a>
    </div>

    <!-- Kost Header -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="height: 350px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); position: relative;">
            @if ($kost->foto_kost)
                <img src="{{ asset('storage/' . $kost->foto_kost) }}" alt="{{ $kost->nama_kost }}" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 120px; color: rgba(255,255,255,0.5);">🏢</span>
                </div>
            @endif
        </div>

        <div style="padding: 30px;">
            <h1 style="font-size: 28px; color: #222; margin-bottom: 10px; font-weight: 700;">{{ $kost->nama_kost }}</h1>
            <p style="font-size: 15px; color: #666; margin-bottom: 20px;">📍 {{ $kost->alamat }}</p>

            <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 25px;">
                <a href="https://wa.me/{{ $kost->pemilik->no_hp }}?text=Halo, saya tertarik dengan {{ $kost->nama_kost }}" 
                   target="_blank"
                   style="padding: 12px 24px; background: #25D366; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    💬 Hubungi Pemilik via WhatsApp
                </a>
                @auth
                    @if (auth()->user()->role === 'penyewa')
                        <a href="#daftar-kamar" 
                           style="padding: 12px 24px; background: #970747; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
                            🛏️ Lihat Kamar Tersedia
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Info Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">👤 Pemilik</p>
                    <p style="font-size: 16px; color: #222; font-weight: 600;">{{ $kost->pemilik->nama_lengkap }}</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📞 Kontak</p>
                    <p style="font-size: 16px; color: #222; font-weight: 600;">{{ $kost->pemilik->no_hp }}</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">🛏️ Kamar Tersedia</p>
                    <p style="font-size: 16px; color: #970747; font-weight: 700;">{{ $kost->rooms->count() }} Kamar</p>
                </div>
            </div>

            <!-- Description -->
            @if ($kost->deskripsi)
                <div style="margin-bottom: 25px;">
                    <h3 style="font-size: 18px; color: #222; margin-bottom: 12px; font-weight: 700;">📋 Deskripsi</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kost->deskripsi }}</p>
                </div>
            @endif

            <!-- Facilities -->
            @if ($kost->fasilitas_umum)
                <div style="margin-bottom: 25px;">
                    <h3 style="font-size: 18px; color: #222; margin-bottom: 12px; font-weight: 700;">🏷️ Fasilitas Umum</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kost->fasilitas_umum }}</p>
                </div>
            @endif

            <!-- Rules -->
            @if ($kost->peraturan)
                <div style="margin-bottom: 25px;">
                    <h3 style="font-size: 18px; color: #222; margin-bottom: 12px; font-weight: 700;">📜 Peraturan</h3>
                    <p style="color: #666; line-height: 1.8;">{{ $kost->peraturan }}</p>
                </div>
            @endif

            <!-- Location -->
            @if ($kost->latitude && $kost->longitude)
                <div style="margin-bottom: 25px;">
                    <h3 style="font-size: 18px; color: #222; margin-bottom: 12px; font-weight: 700;">📍 Lokasi</h3>
                    <p style="color: #666; margin-bottom: 15px;">
                        Latitude: {{ $kost->latitude }} | Longitude: {{ $kost->longitude }}
                    </p>
                    <a href="https://www.google.com/maps?q={{ $kost->latitude }},{{ $kost->longitude }}" 
                       target="_blank"
                       style="display: inline-block; padding: 10px 20px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                        🗺️ Buka di Google Maps
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Available Rooms -->
    @if ($kost->rooms->count() > 0)
        <div id="daftar-kamar" style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <h2 style="font-size: 22px; color: #222; margin-bottom: 20px; font-weight: 700;">
                🛏️ Kamar Tersedia ({{ $kost->rooms->count() }})
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                @foreach ($kost->rooms as $room)
                    <div style="background: #f8f9fa; border-radius: 10px; overflow: hidden; border: 1px solid #eee;">
                        <div style="height: 150px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center;">
                            @if ($room->foto_kamar)
                                <img src="{{ asset('storage/' . $room->foto_kamar) }}" alt="{{ $room->nomor_kamar }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 50px; color: rgba(255,255,255,0.5);">🛏️</span>
                            @endif
                        </div>
                        <div style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                <h4 style="font-size: 15px; color: #222; margin: 0; font-weight: 700;">Kamar {{ $room->nomor_kamar }}</h4>
                                @if ($room->lantai)
                                    <span style="font-size: 10px; color: #666; background: #e0e0e0; padding: 2px 6px; border-radius: 8px;">
                                        Lt. {{ $room->lantai }}
                                    </span>
                                @endif
                            </div>

                            @if ($room->ukuran_kamar)
                                <p style="font-size: 12px; color: #888; margin-bottom: 8px;">📐 {{ $room->ukuran_kamar }}</p>
                            @endif

                            @if ($room->fasilitas_kamar)
                                <p style="font-size: 11px; color: #888; margin-bottom: 12px; line-height: 1.4;">
                                    🏷️ {{ Str::limit($room->fasilitas_kamar, 40) }}
                                </p>
                            @endif

                            <p style="font-size: 18px; color: #970747; font-weight: 700; margin: 10px 0;">
                                Rp {{ number_format($room->harga_per_bulan, 0, ',', '.') }}
                                <span style="font-size: 12px; color: #666; font-weight: 400;">/bulan</span>
                            </p>

                            <div style="display: flex; gap: 6px; margin-top: 12px;">
                                <a href="https://wa.me/{{ $kost->pemilik->no_hp }}?text=Halo, saya tertarik dengan Kamar {{ $room->nomor_kamar }} di {{ $kost->nama_kost }}" 
                                   target="_blank"
                                   style="flex: 1; padding: 8px; background: #25D366; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center;">
                                    💬 WA
                                </a>
                                @auth
                                    @if (auth()->user()->role === 'penyewa')
                                        <a href="{{ route('pesan.create', $room->id_kamar) }}" 
                                           style="flex: 1; padding: 8px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center;">
                                            📝 Pesan
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
