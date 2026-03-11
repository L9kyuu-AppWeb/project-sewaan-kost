@extends('layouts.app')

@section('title', 'Manajemen Kost - Sewa An Kost')

@section('content')
<div class="container-full" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">🏢 Manajemen Kost</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Kelola properti kost Anda</p>
        </div>
        <a href="{{ route('kost.create') }}" class="btn" style="width: auto; padding: 12px 24px;">
            ➕ Tambah Kost Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($kosts->count() > 0)
        <div style="display: grid; gap: 20px;">
            @foreach ($kosts as $k)
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; gap: 20px; align-items: start; flex-wrap: wrap;">
                    <div style="width: 150px; height: 120px; flex-shrink: 0; background: #f0f0f0; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        @if ($k->foto_kost)
                            <img src="{{ asset('storage/' . $k->foto_kost) }}" alt="{{ $k->nama_kost }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 40px; color: #ccc;">🏢</span>
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 250px;">
                        <h3 style="font-size: 18px; color: #333; margin-bottom: 8px;">{{ $k->nama_kost }}</h3>
                        <p style="color: #666; font-size: 14px; margin-bottom: 5px;">📍 {{ $k->alamat }}</p>
                        @if ($k->fasilitas_umum)
                            <p style="color: #888; font-size: 13px; margin-bottom: 10px;">🏷️ {{ Str::limit($k->fasilitas_umum, 80) }}</p>
                        @endif
                        <div style="display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
                            <a href="{{ route('kost.show', $k->id_kost) }}" style="padding: 8px 16px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                👁️ Detail
                            </a>
                            <a href="{{ route('kost.edit', $k->id_kost) }}" style="padding: 8px 16px; background: #c41e6a; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('kost.settings.edit', $k->id_kost) }}" style="padding: 8px 16px; background: #4facfe; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                ⚙️ Fitur
                            </a>
                            <form action="{{ route('kost.destroy', $k->id_kost) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kost ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 8px 16px; background: #e03a6a; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <div style="text-align: right; min-width: 100px;">
                        <p style="font-size: 12px; color: #999;">Dibuat</p>
                        <p style="font-size: 13px; color: #666;">{{ $k->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 20px;">
            {{ $kosts->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">🏢</span>
            <h3 style="color: #970747; margin-bottom: 10px;">Belum Ada Kost</h3>
            <p style="color: #666; margin-bottom: 20px;">Anda belum menambahkan properti kost apapun.</p>
            <a href="{{ route('kost.create') }}" class="btn" style="width: auto; padding: 12px 24px; display: inline-block;">
                ➕ Tambah Kost Pertama
            </a>
        </div>
    @endif
</div>
@endsection
