@extends('layouts.app')

@section('title', 'Manajemen Kamar - Sewa An Kost')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">🛏️ Manajemen Kamar</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Kelola kamar kost Anda</p>
        </div>
        <a href="{{ route('kamar.create') }}" class="btn" style="width: auto; padding: 12px 24px;">
            ➕ Tambah Kamar Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter by Kost -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form action="{{ route('kamar.index') }}" method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label for="kost_id" style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Filter berdasarkan Kost:</label>
                <select name="kost_id" id="kost_id" onchange="this.form.submit()" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Kost</option>
                    @foreach ($kosts as $k)
                        <option value="{{ $k->id_kost }}" {{ $kostId == $k->id_kost ? 'selected' : '' }}>
                            {{ $k->nama_kost }}
                        </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('kamar.index') }}" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                Reset
            </a>
        </form>
    </div>

    @if ($kamars->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @foreach ($kamars as $k)
                <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 180px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center;">
                        @if ($k->foto_kamar)
                            <img src="{{ asset('storage/' . $k->foto_kamar) }}" alt="{{ $k->nomor_kamar }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 60px; color: rgba(255,255,255,0.5);">🛏️</span>
                        @endif
                    </div>
                    <div style="padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <div>
                                <h3 style="font-size: 18px; color: #333; margin-bottom: 5px;">Kamar {{ $k->nomor_kamar }}</h3>
                                <p style="font-size: 13px; color: #666;">🏢 {{ $k->kost->nama_kost }}</p>
                            </div>
                            <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; color: white; background: {{ $k->statusBadge }};">
                                {{ $k->statusLabel }}
                            </span>
                        </div>
                        
                        <div style="margin: 15px 0; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                            <p style="font-size: 13px; color: #666; margin-bottom: 5px;">
                                <span style="font-weight: 600;">📍 Lantai:</span> {{ $k->lantai ?? '-' }}
                            </p>
                            <p style="font-size: 13px; color: #666; margin-bottom: 5px;">
                                <span style="font-weight: 600;">📐 Ukuran:</span> {{ $k->ukuran_kamar ?? '-' }}
                            </p>
                            <p style="font-size: 16px; color: #970747; font-weight: 700; margin: 10px 0 0;">
                                {{ $k->formattedPrice }}<span style="font-size: 12px; color: #666; font-weight: 400;">/bulan</span>
                            </p>
                        </div>

                        @if ($k->fasilitas_kamar)
                            <p style="font-size: 12px; color: #888; margin-bottom: 15px;">🏷️ {{ Str::limit($k->fasilitas_kamar, 50) }}</p>
                        @endif

                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a href="{{ route('kamar.show', $k->id_kamar) }}" style="flex: 1; padding: 8px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; text-align: center;">
                                👁️ Detail
                            </a>
                            <a href="{{ route('kamar.edit', $k->id_kamar) }}" style="flex: 1; padding: 8px; background: #c41e6a; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; text-align: center;">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('kamar.destroy', $k->id_kamar) }}" method="POST" style="flex: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 8px 12px; background: #e03a6a; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 20px;">
            {{ $kamars->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">🛏️</span>
            <h3 style="color: #970747; margin-bottom: 10px;">Belum Ada Kamar</h3>
            <p style="color: #666; margin-bottom: 20px;">Anda belum menambahkan kamar apapun.</p>
            <a href="{{ route('kamar.create') }}" class="btn" style="width: auto; padding: 12px 24px; display: inline-block;">
                ➕ Tambah Kamar Pertama
            </a>
        </div>
    @endif
</div>
@endsection
