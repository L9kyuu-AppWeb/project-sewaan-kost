<?php $__env->startSection('title', 'Dashboard Pemilik - Sewa An Kost'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; color: white; margin-bottom: 5px;">🏠 Dashboard Pemilik</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Kelola properti dan pantau pembayaran penyewa</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">📋 Total Pemesanan</p>
            <p style="font-size: 32px; font-weight: 700;"><?php echo e($stats['total'] ?? 0); ?></p>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">⏳ Menunggu Pembayaran</p>
            <p style="font-size: 32px; font-weight: 700;"><?php echo e($stats['menunggu_pembayaran'] ?? 0); ?></p>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">✅ Aktif</p>
            <p style="font-size: 32px; font-weight: 700;"><?php echo e($stats['aktif'] ?? 0); ?></p>
        </div>
        <div style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); padding: 20px; border-radius: 12px; color: white;">
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">⏳ Pembayaran Pending</p>
            <p style="font-size: 32px; font-weight: 700;"><?php echo e($stats['pending_payments'] ?? 0); ?></p>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form action="<?php echo e(route('pesan.owner.index')); ?>" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <label style="display: block; font-size: 13px; color: #666; margin-bottom: 5px;">Cari Penyewa / Kost</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nama penyewa atau kost..." 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; font-size: 13px; color: #666; margin-bottom: 5px;">Filter Status</label>
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_pembayaran" <?php echo e(request('status') == 'menunggu_pembayaran' ? 'selected' : ''); ?>>Menunggu Pembayaran</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                    <option="dibatalkan" <?php echo e(request('status') == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                </select>
            </div>
            <div style="display: flex; align-items: flex-end; gap: 10px;">
                <button type="submit" class="btn" style="flex: 1; background: #970747;">
                    🔍 Filter
                </button>
                <a href="<?php echo e(route('pesan.owner.index')); ?>" class="btn" style="background: #6c757d; min-width: 100px; text-align: center;">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Pemesanan List -->
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <h2 style="font-size: 18px; color: #222; margin-bottom: 20px; font-weight: 700;">📋 Daftar Pemesanan</h2>

        <?php if($pesanans->count() > 0): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e0e0e0;">
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Penyewa</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Kost / Kamar</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Tanggal</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Durasi</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Total</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Status</th>
                            <th style="padding: 12px; text-align: left; font-size: 13px; color: #666; font-weight: 600;">Pembayaran</th>
                            <th style="padding: 12px; text-align: center; font-size: 13px; color: #666; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $latestPayment = $pesan->latestPayment;
                                $paymentStatus = $latestPayment?->transaction_status ?? '-';
                                $paymentBadge = match($paymentStatus) {
                                    'settlement', 'capture' => 'background: #d4edda; color: #155724;',
                                    'pending' => 'background: #fff3cd; color: #856404;',
                                    'cancel', 'deny', 'expire' => 'background: #f8d7da; color: #721c24;',
                                    default => 'background: #e2e3e5; color: #383d41;',
                                };
                            ?>
                            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;"
                                onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                                <td style="padding: 15px 12px;">
                                    <div>
                                        <p style="font-size: 14px; color: #222; font-weight: 600; margin: 0;"><?php echo e($pesan->penyewa->nama_lengkap); ?></p>
                                        <p style="font-size: 12px; color: #999; margin: 3px 0 0;"><?php echo e($pesan->penyewa->email); ?></p>
                                    </div>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <div>
                                        <p style="font-size: 14px; color: #222; font-weight: 600; margin: 0;"><?php echo e($pesan->kamar->kost->nama_kost); ?></p>
                                        <p style="font-size: 12px; color: #999; margin: 3px 0 0;">Kamar <?php echo e($pesan->kamar->nomor_kamar); ?></p>
                                    </div>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <div>
                                        <p style="font-size: 13px; color: #666; margin: 0;"><?php echo e($pesan->tgl_mulai->format('d M Y')); ?></p>
                                        <p style="font-size: 12px; color: #999; margin: 3px 0 0;">s/d <?php echo e($pesan->tgl_selesai->format('d M Y')); ?></p>
                                    </div>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <span style="font-size: 13px; color: #666;"><?php echo e($pesan->durasi_bulan); ?> bulan</span>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <span style="font-size: 14px; color: #222; font-weight: 600;">Rp <?php echo e(number_format($pesan->total_harga, 0, ',', '.')); ?></span>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <?php
                                        $statusBadge = match($pesan->status_pesan) {
                                            'menunggu_pembayaran' => 'background: #fff3cd; color: #856404;',
                                            'proses_verifikasi' => 'background: #d1ecf1; color: #0c5460;',
                                            'aktif' => 'background: #d4edda; color: #155724;',
                                            'selesai' => 'background: #e2e3e5; color: #383d41;',
                                            'dibatalkan' => 'background: #f8d7da; color: #721c24;',
                                            default => 'background: #666; color: white;',
                                        };
                                    ?>
                                    <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; <?php echo e($statusBadge); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $pesan->status_pesan))); ?>

                                    </span>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <?php if($latestPayment): ?>
                                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; <?php echo e($paymentBadge); ?>">
                                            <?php echo e(ucfirst($paymentStatus)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #999;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 15px 12px; text-align: center;">
                                    <a href="<?php echo e(route('pesan.owner.show', $pesan->id_pesan)); ?>" 
                                       style="display: inline-block; padding: 6px 16px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: background 0.2s;"
                                       onmouseover="this.style.background='#c41e6a'" onmouseout="this.style.background='#970747'">
                                        👁️ Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                <?php echo e($pesanans->links()); ?>

            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <span style="font-size: 80px; display: block; margin-bottom: 20px;">📋</span>
                <h3 style="color: #222; margin-bottom: 10px; font-size: 20px;">Belum Ada Pemesanan</h3>
                <p style="color: #666;">Belum ada pemesanan kamar pada kost Anda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/pesan/owner/index.blade.php ENDPATH**/ ?>