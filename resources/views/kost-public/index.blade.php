@extends('layouts.app')

@section('title', 'Cari Kost - Sewa An Kost')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">🔍 Cari Kost</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Temukan kost impian Anda</p>
    </div>

    <!-- Search & Filter Box -->
    <div style="background: white; border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form action="{{ route('kost-public.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">🔎 Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama kost, alamat..." 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">📍 Lokasi</label>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="Jakarta, Bandung..." 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">💰 Harga Min (Rp)</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">💰 Harga Max (Rp)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000000" 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">🏷️ Fasilitas</label>
                    <input type="text" name="facilities" value="{{ request('facilities') }}" placeholder="WiFi, AC, Parkir..." 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">📊 Urut</label>
                    <select name="sort" onchange="this.form.submit()" 
                            style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="min-width: 120px;">🔍 Cari</button>
                <a href="{{ route('kost-public.index') }}" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">Reset</a>
                <a href="{{ route('kost-public.rooms') }}" class="btn" style="background: #c41e6a; min-width: 150px; text-align: center;">🛏️ Lihat Kamar</a>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if ($kosts->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px;">
            @foreach ($kosts as $kost)
                <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 200px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); position: relative;">
                        @if ($kost->foto_kost)
                            <img src="{{ asset('storage/' . $kost->foto_kost) }}" alt="{{ $kost->nama_kost }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 80px; color: rgba(255,255,255,0.5);">🏢</span>
                            </div>
                        @endif
                        <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color: #970747;">
                            🛏️ {{ $kost->rooms->count() }} Kamar
                        </div>
                    </div>

                    <div style="padding: 20px;">
                        <h3 style="font-size: 18px; color: #222; margin-bottom: 8px; font-weight: 700;">{{ $kost->nama_kost }}</h3>
                        <p style="font-size: 13px; color: #666; margin-bottom: 15px;">📍 {{ Str::limit($kost->alamat, 60) }}</p>

                        @if ($kost->fasilitas_umum)
                            <div style="margin-bottom: 15px;">
                                <p style="font-size: 12px; color: #888; margin-bottom: 8px;">🏷️ {{ Str::limit($kost->fasilitas_umum, 70) }}</p>
                            </div>
                        @endif

                        @if ($kost->rooms->count() > 0)
                            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Harga mulai dari</p>
                                <p style="font-size: 18px; color: #970747; font-weight: 700;">
                                    Rp {{ number_format($kost->rooms->min('harga_per_bulan'), 0, ',', '.') }}
                                    <span style="font-size: 12px; color: #666; font-weight: 400;">/bulan</span>
                                </p>
                            </div>
                        @endif

                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('kost-public.show', $kost->id_kost) }}" 
                               style="flex: 1; padding: 10px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; text-align: center;">
                                👁️ Lihat Detail
                            </a>
                            <a href="https://wa.me/{{ $kost->pemilik->no_hp }}?text=Halo, saya tertarik dengan {{ $kost->nama_kost }}" 
                               target="_blank"
                               style="padding: 10px 15px; background: #25D366; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                💬 WA
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $kosts->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <span style="font-size: 80px; display: block; margin-bottom: 20px;">🔍</span>
            <h3 style="color: #222; margin-bottom: 10px; font-size: 22px;">Kost Tidak Ditemukan</h3>
            <p style="color: #666; margin-bottom: 25px;">Coba ubah filter pencarian Anda</p>
            <a href="{{ route('kost-public.index') }}" class="btn" style="min-width: 150px;">Reset Filter</a>
        </div>
    @endif
</div>
@endsection
