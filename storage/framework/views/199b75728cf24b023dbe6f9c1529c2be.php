<?php $__env->startSection('title', 'Dashboard Pemilik - Sewa An Kost'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-full">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">🏢 Dashboard Pemilik</h1>
        <p style="color: rgba(255,255,255,0.9);">Kelola properti dan pantau pembayaran penyewa</p>
    </div>

    
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
                <?php echo e(substr(auth()->user()->nama_lengkap, 0, 1)); ?>

            </div>
            <div>
                <h2 style="font-size: 18px; color: #333; margin-bottom: 3px;"><?php echo e(auth()->user()->nama_lengkap); ?></h2>
                <p style="color: #666; font-size: 14px;">
                    <?php echo e(auth()->user()->email); ?> • <?php echo e(auth()->user()->no_hp); ?>

                </p>
                <p style="color: #999; font-size: 12px; margin-top: 5px;">🏢 Pemilik Kost</p>
            </div>
        </div>
    </div>

    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <a href="<?php echo e(route('kost.index')); ?>" style="text-decoration: none; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 25px; border-radius: 12px; color: white; transition: transform 0.2s, box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(151,7,71,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">🏢</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;"><?php echo e($stats['total_kost'] ?? 0); ?></h3>
            <p style="font-size: 13px; opacity: 0.9;">Total Kost</p>
        </a>
        <a href="<?php echo e(route('kamar.index')); ?>" style="text-decoration: none; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 12px; color: white; transition: transform 0.2s, box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(79,172,254,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">🛏️</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;"><?php echo e($stats['total_kamar'] ?? 0); ?></h3>
            <p style="font-size: 13px; opacity: 0.9;">Total Kamar</p>
        </a>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">📊</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;"><?php echo e($stats['kamar_terisi'] ?? 0); ?></h3>
            <p style="font-size: 13px; opacity: 0.9;">Kamar Terisi</p>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 25px; border-radius: 12px; color: white;">
            <span style="font-size: 36px; display: block; margin-bottom: 10px;">👥</span>
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px;"><?php echo e($stats['penyewa_aktif'] ?? 0); ?></h3>
            <p style="font-size: 13px; opacity: 0.9;">Penyewa Aktif</p>
        </div>
    </div>

    
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            📦 Pesanan Layanan
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">🍽️</span>
                    <p style="font-size: 13px; color: #666; font-weight: 600;">Makanan</p>
                </div>
                <p style="font-size: 28px; color: #333; font-weight: 700; margin: 0;"><?php echo e($stats['total_pesanan_makanan'] ?? 0); ?></p>
                <div style="display: flex; gap: 10px; margin-top: 10px; font-size: 12px;">
                    <span style="color: #f093fb;">⏳ <?php echo e($stats['pesanan_makanan_pending'] ?? 0); ?></span>
                    <span style="color: #4facfe;">⚙️ <?php echo e($stats['pesanan_makanan_proses'] ?? 0); ?></span>
                </div>
            </div>

            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">💧</span>
                    <p style="font-size: 13px; color: #666; font-weight: 600;">Galon</p>
                </div>
                <p style="font-size: 28px; color: #333; font-weight: 700; margin: 0;"><?php echo e($stats['total_pesanan_galon'] ?? 0); ?></p>
                <div style="display: flex; gap: 10px; margin-top: 10px; font-size: 12px;">
                    <span style="color: #f093fb;">⏳ <?php echo e($stats['pesanan_galon_pending'] ?? 0); ?></span>
                    <span style="color: #4facfe;">⚙️ <?php echo e($stats['pesanan_galon_proses'] ?? 0); ?></span>
                </div>
            </div>

            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">👕</span>
                    <p style="font-size: 13px; color: #666; font-weight: 600;">Laundry</p>
                </div>
                <p style="font-size: 28px; color: #333; font-weight: 700; margin: 0;"><?php echo e($stats['total_pesanan_laundry'] ?? 0); ?></p>
                <div style="display: flex; gap: 10px; margin-top: 10px; font-size: 12px;">
                    <span style="color: #f093fb;">⏳ <?php echo e($stats['pesanan_laundry_pending'] ?? 0); ?></span>
                    <span style="color: #4facfe;">🧼 <?php echo e($stats['pesanan_laundry_proses'] ?? 0); ?></span>
                </div>
            </div>

            
            <a href="<?php echo e(route('pesan.owner.index')); ?>" style="text-decoration: none; background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); padding: 20px; border-radius: 12px; color: white; transition: transform 0.2s;"
               onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <span style="font-size: 32px;">⏳</span>
                    <p style="font-size: 13px; color: white; font-weight: 600;">Pembayaran Pending</p>
                </div>
                <p style="font-size: 28px; color: white; font-weight: 700; margin: 0;"><?php echo e($stats['pending_payments'] ?? 0); ?></p>
                <p style="font-size: 11px; color: rgba(255,255,255,0.9); margin-top: 5px;">Klik untuk verifikasi →</p>
            </a>
        </div>
    </div>

    
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            💰 Statistik Pendapatan
        </h2>

        
        <div style="border: 2px solid #970747; border-radius: 12px; padding: 25px; background: linear-gradient(135deg, #fee6f0 0%, #fff5f8 100%); margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div>
                    <p style="font-size: 14px; color: #970747; font-weight: 700; margin-bottom: 5px; text-transform: uppercase;">💎 Total Pendapatan (Semua Tipe)</p>
                    <p style="font-size: 12px; color: #666; margin: 0;">Gabungan dari Kamar, Makanan, Galon, dan Laundry</p>
                </div>
                <span style="font-size: 42px;">🏆</span>
            </div>
            <p style="font-size: 36px; color: #970747; font-weight: 800; margin: 0;">
                Rp <?php echo e(number_format($stats['pendapatan_total'] ?? 0, 0, ',', '.')); ?>

            </p>
            <div style="display: flex; gap: 20px; margin-top: 15px; flex-wrap: wrap;">
                <p style="font-size: 13px; color: #666;">
                    <strong>Bulan Ini:</strong> 
                    <span style="color: #970747; font-weight: 600;">Rp <?php echo e(number_format($stats['pendapatan_bulan_ini'] ?? 0, 0, ',', '.')); ?></span>
                </p>
                <p style="font-size: 13px; color: #666;">
                    <strong>Tahun Ini:</strong> 
                    <span style="color: #970747; font-weight: 600;">Rp <?php echo e(number_format($stats['pendapatan_tahun_ini'] ?? 0, 0, ',', '.')); ?></span>
                </p>
            </div>
        </div>

        <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📊 Berdasarkan Tipe Layanan</h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #666; font-weight: 600; margin-bottom: 5px;">🏠 Sewa Kamar</p>
                    </div>
                    <span style="font-size: 28px;">🔑</span>
                </div>
                <p style="font-size: 22px; color: #333; font-weight: 700; margin: 0;">
                    Rp <?php echo e(number_format($stats['pendapatan_kamar_total'] ?? 0, 0, ',', '.')); ?>

                </p>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.1);">
                    <p style="font-size: 11px; color: #666; margin: 3px 0;">
                        <strong>Bulan Ini:</strong> Rp <?php echo e(number_format($stats['pendapatan_kamar_bulan_ini'] ?? 0, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>

            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #666; font-weight: 600; margin-bottom: 5px;">🍽️ Makanan</p>
                    </div>
                    <span style="font-size: 28px;">🍽️</span>
                </div>
                <p style="font-size: 22px; color: #333; font-weight: 700; margin: 0;">
                    Rp <?php echo e(number_format($stats['pendapatan_makanan_total'] ?? 0, 0, ',', '.')); ?>

                </p>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.1);">
                    <p style="font-size: 11px; color: #666; margin: 3px 0;">
                        <strong>Bulan Ini:</strong> Rp <?php echo e(number_format($stats['pendapatan_makanan_bulan_ini'] ?? 0, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>

            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #666; font-weight: 600; margin-bottom: 5px;">💧 Galon</p>
                    </div>
                    <span style="font-size: 28px;">💧</span>
                </div>
                <p style="font-size: 22px; color: #333; font-weight: 700; margin: 0;">
                    Rp <?php echo e(number_format($stats['pendapatan_galon_total'] ?? 0, 0, ',', '.')); ?>

                </p>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.1);">
                    <p style="font-size: 11px; color: #666; margin: 3px 0;">
                        <strong>Bulan Ini:</strong> Rp <?php echo e(number_format($stats['pendapatan_galon_bulan_ini'] ?? 0, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>

            
            <div style="border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div>
                        <p style="font-size: 13px; color: #666; font-weight: 600; margin-bottom: 5px;">👕 Laundry</p>
                    </div>
                    <span style="font-size: 28px;">👕</span>
                </div>
                <p style="font-size: 22px; color: #333; font-weight: 700; margin: 0;">
                    Rp <?php echo e(number_format($stats['pendapatan_laundry_total'] ?? 0, 0, ',', '.')); ?>

                </p>
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.1);">
                    <p style="font-size: 11px; color: #666; margin: 3px 0;">
                        <strong>Bulan Ini:</strong> Rp <?php echo e(number_format($stats['pendapatan_laundry_bulan_ini'] ?? 0, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            💳 Status Pembayaran Midtrans
        </h2>

        <div style="display: grid; gap: 15px;">
            <div style="display: flex; gap: 12px; padding: 15px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <span style="font-size: 24px;">⏳</span>
                <div style="flex: 1;">
                    <p style="font-size: 14px; font-weight: 600; color: #856404; margin-bottom: 5px;">Pending</p>
                    <p style="font-size: 13px; color: #856404; margin: 0;">Penyewa belum menyelesaikan pembayaran. Pembayaran akan otomatis diverifikasi setelah lunas.</p>
                </div>
            </div>

            <div style="display: flex; gap: 12px; padding: 15px; background: #d4edda; border-radius: 8px; border-left: 4px solid #28a745;">
                <span style="font-size: 24px;">✅</span>
                <div style="flex: 1;">
                    <p style="font-size: 14px; font-weight: 600; color: #155724; margin-bottom: 5px;">Settlement (Berhasil)</p>
                    <p style="font-size: 13px; color: #155724; margin: 0;">Pembayaran berhasil. Status pemesanan otomatis berubah menjadi "Aktif" dan kamar terisi.</p>
                </div>
            </div>

            <div style="display: flex; gap: 12px; padding: 15px; background: #f8d7da; border-radius: 8px; border-left: 4px solid #dc3545;">
                <span style="font-size: 24px;">❌</span>
                <div style="flex: 1;">
                    <p style="font-size: 14px; font-weight: 600; color: #721c24; margin-bottom: 5px;">Dibatalkan / Ditolak / Expired</p>
                    <p style="font-size: 13px; color: #721c24; margin: 0;">Pembayaran dibatalkan atau expired. Penyewa perlu membuat pemesanan baru.</p>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 8px; border-left: 4px solid #2196F3;">
            <p style="font-size: 13px; color: #1565C0; margin: 0;">
                ℹ️ <strong>Catatan:</strong> Semua pembayaran diproses otomatis melalui Midtrans. Tidak perlu verifikasi manual. 
                Status kamar dan pemesanan akan update otomatis setelah pembayaran berhasil.
            </p>
        </div>
    </div>

    
    <?php
        $recentPesanans = \App\Models\Pesan::with(['penyewa', 'kamar.kost', 'latestPayment'])
            ->whereHas('kamar.kost', function ($q) {
                $q->where('id_pemilik', auth()->id());
            })
            ->latest('id_pesan')
            ->limit(5)
            ->get();
    ?>

    <?php if($recentPesanans->count() > 0): ?>
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; color: #333; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            📋 Pemesanan Terbaru
        </h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Penyewa</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Kost / Kamar</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Tanggal</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Status</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $recentPesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $latestPayment = $pesan->latestPayment;
                            $paymentStatus = $latestPayment?->transaction_status ?? '-';
                            $paymentBadge = match($paymentStatus) {
                                'settlement', 'capture' => 'background: #d4edda; color: #155724;',
                                'pending' => 'background: #fff3cd; color: #856404;',
                                'cancel', 'deny', 'expire' => 'background: #f8d7da; color: #721c24;',
                                default => 'background: #e2e3e5; color: #383d41;',
                            };
                            $statusBadge = match($pesan->status_pesan) {
                                'menunggu_pembayaran' => 'background: #fff3cd; color: #856404;',
                                'proses_verifikasi' => 'background: #d1ecf1; color: #0c5460;',
                                'aktif' => 'background: #d4edda; color: #155724;',
                                'selesai' => 'background: #e2e3e5; color: #383d41;',
                                'dibatalkan' => 'background: #f8d7da; color: #721c24;',
                                default => 'background: #666; color: white;',
                            };
                        ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 12px;">
                                <p style="font-size: 14px; color: #222; font-weight: 600; margin: 0;"><?php echo e($pesan->penyewa->nama_lengkap); ?></p>
                                <p style="font-size: 12px; color: #999; margin: 3px 0 0;"><?php echo e($pesan->penyewa->email); ?></p>
                            </td>
                            <td style="padding: 12px;">
                                <p style="font-size: 14px; color: #222; font-weight: 600; margin: 0;"><?php echo e($pesan->kamar->kost->nama_kost); ?></p>
                                <p style="font-size: 12px; color: #999; margin: 3px 0 0;">Kamar <?php echo e($pesan->kamar->nomor_kamar); ?></p>
                            </td>
                            <td style="padding: 12px;">
                                <p style="font-size: 13px; color: #666; margin: 0;"><?php echo e($pesan->tgl_mulai->format('d M Y')); ?></p>
                            </td>
                            <td style="padding: 12px;">
                                <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; <?php echo e($statusBadge); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $pesan->status_pesan))); ?>

                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <?php if($latestPayment): ?>
                                    <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; <?php echo e($paymentBadge); ?>">
                                        <?php echo e(ucfirst($paymentStatus)); ?>

                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="<?php echo e(route('pesan.owner.index')); ?>" style="display: inline-block; padding: 10px 24px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; transition: background 0.2s;"
               onmouseover="this.style.background='#c41e6a'" onmouseout="this.style.background='#970747'">
                Lihat Semua Pemesanan →
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/dashboard/pemilik.blade.php ENDPATH**/ ?>