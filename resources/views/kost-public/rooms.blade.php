@extends('layouts.app')

@section('title', 'Cari Kamar - Sewa An Kost')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">🛏️ Cari Kamar</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Pilih kamar yang sesuai dengan kebutuhan Anda</p>
    </div>

    <!-- Search & Filter Box -->
    <div style="background: white; border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form action="{{ route('kost-public.rooms') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">🔎 Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="No. kamar, nama kost..." 
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
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">🏢 Lantai</label>
                    <select name="floor" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        <option value="">Semua</option>
                        <option value="0" {{ request('floor') === '0' ? 'selected' : '' }}>Dasar</option>
                        <option value="1" {{ request('floor') === '1' ? 'selected' : '' }}>Lantai 1</option>
                        <option value="2" {{ request('floor') === '2' ? 'selected' : '' }}>Lantai 2</option>
                        <option value="3" {{ request('floor') === '3' ? 'selected' : '' }}>Lantai 3</option>
                        <option value="4" {{ request('floor') === '4' ? 'selected' : '' }}>Lantai 4+</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 13px;">🏷️ Fasilitas</label>
                    <input type="text" name="facilities" value="{{ request('facilities') }}" placeholder="AC, Kamar mandi..." 
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
                <a href="{{ route('kost-public.rooms') }}" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">Reset</a>
                <a href="{{ route('kost-public.index') }}" class="btn" style="background: #970747; min-width: 150px; text-align: center;">🏢 Lihat Kost</a>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if ($kamars->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach ($kamars as $kamar)
                <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 180px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); position: relative;">
                        @if ($kamar->foto_kamar)
                            <img src="{{ asset('storage/' . $kamar->foto_kamar) }}" alt="{{ $kamar->nomor_kamar }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 60px; color: rgba(255,255,255,0.5);">🛏️</span>
                            </div>
                        @endif
                        <div style="position: absolute; top: 10px; left: 10px; background: #43e97b; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; color: white;">
                            TERSEDIA
                        </div>
                    </div>

                    <div style="padding: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <h3 style="font-size: 16px; color: #222; margin: 0; font-weight: 700;">Kamar {{ $kamar->nomor_kamar }}</h3>
                            @if ($kamar->lantai)
                                <span style="font-size: 11px; color: #666; background: #f0f0f0; padding: 3px 8px; border-radius: 10px;">
                                    🏢 Lt. {{ $kamar->lantai }}
                                </span>
                            @endif
                        </div>

                        <p style="font-size: 12px; color: #666; margin-bottom: 10px;">🏢 {{ $kamar->kost->nama_kost }}</p>

                        @if ($kamar->ukuran_kamar)
                            <p style="font-size: 12px; color: #888; margin-bottom: 8px;">📐 {{ $kamar->ukuran_kamar }}</p>
                        @endif

                        @if ($kamar->fasilitas_kamar)
                            <p style="font-size: 11px; color: #888; margin-bottom: 12px; line-height: 1.5;">
                                🏷️ {{ Str::limit($kamar->fasilitas_kamar, 50) }}
                            </p>
                        @endif

                        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                            <p style="font-size: 18px; color: white; font-weight: 700; margin: 0;">
                                Rp {{ number_format($kamar->harga_per_bulan, 0, ',', '.') }}
                                <span style="font-size: 11px; color: rgba(255,255,255,0.8); font-weight: 400;">/bulan</span>
                            </p>
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('kost-public.show', $kamar->id_kost) }}" 
                               style="flex: 1; padding: 8px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center;">
                                👁️ Detail
                            </a>
                            <a href="https://wa.me/{{ $kamar->kost->pemilik->no_hp }}?text=Halo, saya tertarik dengan Kamar {{ $kamar->nomor_kamar }} di {{ $kamar->kost->nama_kost }}" 
                               target="_blank"
                               style="padding: 8px 12px; background: #25D366; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                💬
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $kamars->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <span style="font-size: 80px; display: block; margin-bottom: 20px;">🔍</span>
            <h3 style="color: #222; margin-bottom: 10px; font-size: 22px;">Kamar Tidak Ditemukan</h3>
            <p style="color: #666; margin-bottom: 25px;">Coba ubah filter pencarian Anda</p>
            <a href="{{ route('kost-public.rooms') }}" class="btn" style="min-width: 150px;">Reset Filter</a>
        </div>
    @endif
</div>
@endsection
