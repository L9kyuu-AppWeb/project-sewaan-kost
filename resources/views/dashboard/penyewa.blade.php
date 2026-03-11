@extends('layouts.app')

@section('title', 'Dashboard Penyewa - Sewa An Kost')

@section('content')
<div class="container-full">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">🏠 Dashboard Penyewa</h1>
        <p style="color: rgba(255,255,255,0.9);">Selamat datang, {{ auth()->user()->nama_lengkap }}!</p>
    </div>

    {{-- Profile Card --}}
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
                {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
            </div>
            <div>
                <h2 style="font-size: 18px; color: #333; margin-bottom: 3px;">{{ auth()->user()->nama_lengkap }}</h2>
                <p style="color: #666; font-size: 14px;">
                    {{ auth()->user()->email }} • {{ auth()->user()->no_hp }}
                </p>
                @if (auth()->user()->nik)
                    <p style="color: #999; font-size: 12px; margin-top: 5px;">NIK: {{ auth()->user()->nik }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <a href="{{ route('kost-public.index') }}" style="text-decoration: none; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 25px; border-radius: 12px; color: white; transition: transform 0.2s, box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(151,7,71,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">🔍</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">Cari Kost</h3>
            <p style="font-size: 13px; opacity: 0.9;">Temukan kost impian Anda</p>
        </a>
        <a href="{{ route('pesan.index') }}" style="text-decoration: none; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 12px; color: white; transition: transform 0.2s, box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(79,172,254,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">📋</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['total_pemesanan'] ?? 0 }}</h3>
            <p style="font-size: 13px; opacity: 0.9;">Total Pemesanan</p>
        </a>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">✅</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['pemesanan_aktif'] ?? 0 }}</h3>
            <p style="font-size: 13px; opacity: 0.9;">Pemesanan Aktif</p>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">⏳</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;">{{ $stats['pemesanan_pending'] ?? 0 }}</h3>
            <p style="font-size: 13px; opacity: 0.9;">Menunggu Pembayaran</p>
        </div>
    </div>

    {{-- Service Orders Summary --}}
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            📦 Pesanan Layanan Anda
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            {{-- Makanan Orders --}}
            <a href="{{ route('orders.index') }}" style="text-decoration: none; border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='translateY(0)';">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">🍽️</span>
                    <p style="font-size: 13px; color: #666; font-weight: 600;">Makanan</p>
                </div>
                <p style="font-size: 28px; color: #333; font-weight: 700; margin: 0;">{{ $stats['total_pesanan_makanan'] ?? 0 }}</p>
                <div style="display: flex; gap: 10px; margin-top: 10px; font-size: 12px;">
                    <span style="color: #f093fb;">⏳ {{ $stats['pesanan_makanan_pending'] ?? 0 }}</span>
                    <span style="color: #4facfe;">🚚 {{ $stats['pesanan_makanan_proses'] ?? 0 }}</span>
                </div>
                <p style="font-size: 11px; color: #666; margin-top: 10px;">Klik untuk lihat →</p>
            </a>

            {{-- Galon Orders --}}
            <a href="{{ route('galon.orders.index') }}" style="text-decoration: none; border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='translateY(0)';">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">💧</span>
                    <p style="font-size: 13px; color: #666; font-weight: 600;">Galon</p>
                </div>
                <p style="font-size: 28px; color: #333; font-weight: 700; margin: 0;">{{ $stats['total_pesanan_galon'] ?? 0 }}</p>
                <div style="display: flex; gap: 10px; margin-top: 10px; font-size: 12px;">
                    <span style="color: #f093fb;">⏳ {{ $stats['pesanan_galon_pending'] ?? 0 }}</span>
                    <span style="color: #4facfe;">🚚 {{ $stats['pesanan_galon_proses'] ?? 0 }}</span>
                </div>
                <p style="font-size: 11px; color: #666; margin-top: 10px;">Klik untuk lihat →</p>
            </a>

            {{-- Laundry Orders --}}
            <a href="{{ route('laundry.orders.index') }}" style="text-decoration: none; border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%); transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='translateY(0)';">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">👕</span>
                    <p style="font-size: 13px; color: #666; font-weight: 600;">Laundry</p>
                </div>
                <p style="font-size: 28px; color: #333; font-weight: 700; margin: 0;">{{ $stats['total_pesanan_laundry'] ?? 0 }}</p>
                <div style="display: flex; gap: 10px; margin-top: 10px; font-size: 12px;">
                    <span style="color: #f093fb;">⏳ {{ $stats['pesanan_laundry_pending'] ?? 0 }}</span>
                    <span style="color: #4facfe;">🧼 {{ $stats['pesanan_laundry_proses'] ?? 0 }}</span>
                </div>
                <p style="font-size: 11px; color: #666; margin-top: 10px;">Klik untuk lihat →</p>
            </a>
        </div>
    </div>

    {{-- Multiple Active Rooms/Kost Info --}}
    @if($stats['pesan_aktif_list'] && $stats['pesan_aktif_list']->count() > 0)
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            🏠 Kamar & Kost Aktif Anda ({{ $stats['pesan_aktif_list']->count() }})
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
            @foreach($stats['pesan_aktif_list'] as $pesan)
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;"
                 onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.12)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                
                {{-- Card Header --}}
                <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 15px; color: white;">
                    <h3 style="font-size: 16px; margin: 0; font-weight: 600;">{{ $pesan->kamar->kost->nama_kost }}</h3>
                    <p style="font-size: 12px; margin: 5px 0 0; opacity: 0.9;">{{ $pesan->kamar->kost->alamat }}</p>
                </div>

                {{-- Card Body --}}
                <div style="padding: 20px;">
                    {{-- Room Info --}}
                    <div style="margin-bottom: 15px;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 5px;">🚪 Kamar</p>
                        <p style="font-size: 16px; color: #333; font-weight: 600; margin: 0;">{{ $pesan->kamar->nomor_kamar }}</p>
                        <p style="font-size: 13px; color: #666; margin: 3px 0 0;">Fasilitas: {{ $pesan->kamar->fasilitas ?? '-' }}</p>
                    </div>

                    {{-- Lease Period --}}
                    <div style="margin-bottom: 15px;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 5px;">📅 Masa Sewa</p>
                        <p style="font-size: 14px; color: #333; margin: 0;">
                            {{ \Carbon\Carbon::parse($pesan->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pesan->tgl_selesai)->format('d M Y') }}
                        </p>
                        <p style="font-size: 13px; color: #666; margin: 3px 0 0;">Durasi: {{ $pesan->durasi_bulan }} bulan</p>
                    </div>

                    {{-- Remaining Days --}}
                    @php
                        $sisaHari = max(0, \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($pesan->tgl_selesai), false));
                        $isExpired = \Carbon\Carbon::parse($pesan->tgl_selesai)->isPast();
                    @endphp
                    <div style="margin-bottom: 15px;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 5px;">⏳ Sisa Masa Sewa</p>
                        @if($isExpired)
                            <p style="font-size: 14px; color: #dc3545; font-weight: 600; margin: 0;">⚠️ Sudah Berakhir</p>
                        @elseif($sisaHari <= 7)
                            <p style="font-size: 14px; color: #dc3545; font-weight: 600; margin: 0;">{{ $sisaHari }} hari lagi</p>
                            <p style="font-size: 11px; color: #dc3545; margin: 3px 0 0;">⚠️ Segera perpanjang!</p>
                        @else
                            <p style="font-size: 14px; color: #28a745; font-weight: 600; margin: 0;">{{ $sisaHari }} hari lagi</p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div style="border-top: 1px solid #e0e0e0; padding-top: 15px; margin-top: 15px;">
                        <p style="font-size: 12px; color: #999; margin-bottom: 5px;">💰 Harga per Bulan</p>
                        <p style="font-size: 18px; color: #970747; font-weight: 700; margin: 0;">Rp {{ number_format($pesan->kamar->harga_per_bulan, 0, ',', '.') }}</p>
                    </div>

                    {{-- View Detail Button --}}
                    <a href="{{ route('pesan.show', $pesan->id_pesan) }}" 
                       style="display: block; text-align: center; padding: 10px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; margin-top: 15px; transition: background 0.2s;"
                       onmouseover="this.style.background='#c41e6a'"
                       onmouseout="this.style.background='#970747'">
                        👁️ Detail Pemesanan
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    {{-- No Active Rooms --}}
    <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <span style="font-size: 80px; display: block; margin-bottom: 20px;">🏠</span>
        <h3 style="color: #333; margin-bottom: 10px; font-size: 22px;">Belum Ada Kamar Aktif</h3>
        <p style="color: #666; margin-bottom: 25px;">Anda belum memiliki pemesanan kamar yang aktif.</p>
        <a href="{{ route('kost-public.index') }}" class="btn" style="min-width: 150px;">🔍 Cari Kost Sekarang</a>
    </div>
    @endif

    {{-- Payment Status Info --}}
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            💳 Status Pembayaran
        </h2>

        @if($stats['pembayaran_pending'])
        {{-- Pending Payment Alert --}}
        <div style="padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 8px; color: white; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <p style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">⏳ Pembayaran Belum Selesai</p>
                    <p style="font-size: 24px; font-weight: 600;">Rp {{ number_format($stats['pembayaran_pending']->jumlah_bayar, 0, ',', '.') }}</p>
                    <p style="font-size: 12px; opacity: 0.8; margin-top: 5px;">
                        Order ID: {{ $stats['pembayaran_pending']->order_id }}
                    </p>
                </div>
                <a href="{{ route('midtrans.pay', $stats['pembayaran_pending']->pesan->id_pesan) }}"
                   style="background: white; color: #f5576c; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">
                    Bayar via Midtrans →
                </a>
            </div>
        </div>
        @endif

        {{-- Payment Status Explanation --}}
        <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
            <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📌 Apa itu Status Pembayaran?</h3>
            <p style="font-size: 14px; color: #666; margin-bottom: 15px; line-height: 1.6;">
                Status Pembayaran menunjukkan status transaksi pembayaran Anda yang terintegrasi dengan <strong>Midtrans</strong>. 
                Status ini membantu Anda memantau apakah pembayaran sudah berhasil, masih pending, atau perlu tindakan lebih lanjut.
            </p>

            <div style="display: grid; gap: 12px;">
                <div style="display: flex; gap: 12px; padding: 12px; background: #fff3cd; border-radius: 6px;">
                    <span style="font-size: 20px;">⏳</span>
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #856404; margin-bottom: 3px;">Pending</p>
                        <p style="font-size: 13px; color: #856404;">Pembayaran belum selesai. Silakan selesaikan pembayaran melalui link yang diberikan.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; padding: 12px; background: #d4edda; border-radius: 6px;">
                    <span style="font-size: 20px;">✅</span>
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #155724; margin-bottom: 3px;">Settlement (Berhasil)</p>
                        <p style="font-size: 13px; color: #155724;">Pembayaran berhasil dan sudah diverifikasi. Kamar sudah aktif untuk Anda tempati.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; padding: 12px; background: #f8d7da; border-radius: 6px;">
                    <span style="font-size: 20px;">❌</span>
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #721c24; margin-bottom: 3px;">Dibatalkan / Ditolak</p>
                        <p style="font-size: 13px; color: #721c24;">Pembayaran dibatalkan oleh Anda atau ditolak oleh sistem. Silakan buat pemesanan baru.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; padding: 12px; background: #e2e3e5; border-radius: 6px;">
                    <span style="font-size: 20px;">⏰</span>
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #383d41; margin-bottom: 3px;">Expired</p>
                        <p style="font-size: 13px; color: #383d41;">Waktu pembayaran telah habis. Silakan buat pemesanan baru jika masih ingin menyewa.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment History --}}
        @php
            $pembayaranHistory = \App\Models\Pembayaran::with('pesan')
                ->whereHas('pesan', function ($q) {
                    $q->where('id_penyewa', auth()->id());
                })
                ->latest('id_pembayaran')
                ->limit(5)
                ->get();
        @endphp

        @if($pembayaranHistory->count() > 0)
        <div style="margin-top: 20px;">
            <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📜 Riwayat Pembayaran</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; border-bottom: 2px solid #e0e0e0;">Order ID</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; border-bottom: 2px solid #e0e0e0;">Tanggal</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; border-bottom: 2px solid #e0e0e0;">Jumlah</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; border-bottom: 2px solid #e0e0e0;">Metode</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; border-bottom: 2px solid #e0e0e0;">Status Midtrans</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaranHistory as $pembayaran)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 12px; font-size: 13px; color: #333;">{{ $pembayaran->order_id }}</td>
                            <td style="padding: 12px; font-size: 13px; color: #666;">{{ $pembayaran->created_at->format('d M Y') }}</td>
                            <td style="padding: 12px; font-size: 13px; color: #333; font-weight: 500;">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                            <td style="padding: 12px; font-size: 13px; color: #666;">{{ $pembayaran->payment_type ? ucfirst(str_replace('_', ' ', $pembayaran->payment_type)) : '-' }}</td>
                            <td style="padding: 12px;">
                                @php
                                    $statusClass = match($pembayaran->transaction_status) {
                                        'settlement', 'capture' => 'background: #d4edda; color: #155724;',
                                        'pending' => 'background: #fff3cd; color: #856404;',
                                        'cancel', 'deny', 'expire' => 'background: #f8d7da; color: #721c24;',
                                        default => 'background: #e2e3e5; color: #383d41;',
                                    };
                                @endphp
                                <span style="padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 500; {{ $statusClass }}">
                                    {{ $pembayaran->transaction_status ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
