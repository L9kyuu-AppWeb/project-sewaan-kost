<?php $__env->startSection('title', 'Detail Pemesanan - Sewa An Kost'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-full" style="padding: 20px; max-width: 900px;">
    <div style="margin-bottom: 20px;">
        <a href="<?php echo e(route('pesan.index')); ?>" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Pemesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="font-size: 24px; color: #222; margin-bottom: 10px;">
                    Detail Pemesanan
                </h1>
                <p style="color: #666; font-size: 14px;">
                    ID Pemesanan: #<?php echo e(str_pad($pesan->id_pesan, 6, '0', STR_PAD_LEFT)); ?>

                </p>
            </div>
            <span style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; color: white; background: <?php echo e($pesan->statusBadge); ?>;">
                <?php echo e($pesan->statusLabel); ?>

            </span>
        </div>

        <!-- Room Info -->
        <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 20px; border-radius: 12px; margin-bottom: 25px; color: white;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 50px;">🛏️</span>
                <div>
                    <h2 style="font-size: 20px; margin-bottom: 5px;">Kamar <?php echo e($pesan->kamar->nomor_kamar); ?></h2>
                    <p style="font-size: 14px; opacity: 0.9;"><?php echo e($pesan->kamar->kost->nama_kost); ?></p>
                </div>
            </div>
        </div>

        <!-- Booking Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">📅 Tanggal Mulai</p>
                <p style="font-size: 16px; color: #222; font-weight: 600;"><?php echo e($pesan->tgl_mulai->format('d M Y')); ?></p>
            </div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">📅 Tanggal Selesai</p>
                <p style="font-size: 16px; color: #222; font-weight: 600;"><?php echo e($pesan->tgl_selesai->format('d M Y')); ?></p>
            </div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">⏱️ Durasi</p>
                <p style="font-size: 16px; color: #222; font-weight: 600;"><?php echo e($pesan->durasi_bulan); ?> bulan</p>
            </div>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">💰 Total Harga</p>
                <p style="font-size: 18px; color: #970747; font-weight: 700;"><?php echo e($pesan->formattedTotalHarga); ?></p>
            </div>
        </div>

        <!-- Tenant Info -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <h3 style="font-size: 16px; color: #222; margin-bottom: 15px; font-weight: 700;">👤 Informasi Penyewa</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Nama</p>
                    <p style="font-size: 14px; color: #222; font-weight: 600;"><?php echo e($pesan->penyewa->nama_lengkap); ?></p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">Email</p>
                    <p style="font-size: 14px; color: #222;"><?php echo e($pesan->penyewa->email); ?></p>
                </div>
                <div>
                    <p style="font-size: 12px; color: #666; margin-bottom: 3px;">No. HP</p>
                    <p style="font-size: 14px; color: #222;"><?php echo e($pesan->penyewa->no_hp); ?></p>
                </div>
                <?php if($pesan->penyewa->nik): ?>
                    <div>
                        <p style="font-size: 12px; color: #666; margin-bottom: 3px;">NIK</p>
                        <p style="font-size: 14px; color: #222;"><?php echo e($pesan->penyewa->nik); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notes -->
        <?php if($pesan->catatan): ?>
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #ffc107;">
                <h3 style="font-size: 14px; color: #856404; margin-bottom: 8px; font-weight: 600;">📝 Catatan</h3>
                <p style="color: #856404; line-height: 1.6;"><?php echo e($pesan->catatan); ?></p>
            </div>
        <?php endif; ?>

        <!-- Payment Info -->
        <?php if($pesan->payments->count() > 0): ?>
            <div style="margin-top: 25px;">
                <h3 style="font-size: 18px; color: #222; margin-bottom: 15px; font-weight: 700;">💳 Riwayat Pembayaran</h3>
                <?php $__currentLoopData = $pesan->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid <?php echo e($payment->statusBadge); ?>;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 10px;">
                            <div style="flex: 1;">
                                <p style="font-size: 14px; color: #222; font-weight: 600; margin-bottom: 5px;">
                                    <?php echo e($payment->payment_type ? ucfirst(str_replace('_', ' ', $payment->payment_type)) : 'Pembayaran'); ?> - Rp <?php echo e(number_format($payment->jumlah_bayar, 0, ',', '.')); ?>

                                </p>
                                <p style="font-size: 12px; color: #666;">
                                    Order ID: <?php echo e($payment->order_id ?? '-'); ?> |
                                    Status: <?php echo e($payment->transaction_status_label); ?>

                                </p>
                                <?php if($payment->transaction_id): ?>
                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                        Transaction ID: <?php echo e($payment->transaction_id); ?>

                                    </p>
                                <?php endif; ?>
                                <?php if($payment->catatan_verifikasi): ?>
                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                        Catatan: <?php echo e($payment->catatan_verifikasi); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                            <div style="text-align: right;">
                                <span style="display: inline-block; padding: 6px 12px; background: <?php echo e($payment->statusBadge); ?>; color: white; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    <?php echo e($payment->statusLabel); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 10px; margin-top: 30px; flex-wrap: wrap;">
            <a href="<?php echo e(route('pesan.index')); ?>" class="btn" style="background: #6c757d;">
                ← Kembali
            </a>

            <?php if($pesan->isPendingPayment()): ?>
                <a href="<?php echo e(route('midtrans.pay', $pesan->id_pesan)); ?>" class="btn" style="background: #43e97b;">
                    💳 Bayar via Midtrans
                </a>
                <form action="<?php echo e(route('pesan.cancel', $pesan->id_pesan)); ?>" method="POST" style="display: inline;"
                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn" style="background: #f5576c;">
                        ❌ Batalkan
                    </button>
                </form>
            <?php endif; ?>

            <?php if($pesan->kamar->kost->pemilik->no_hp): ?>
                <a href="https://wa.me/<?php echo e($pesan->kamar->kost->pemilik->no_hp); ?>?text=Halo, saya <?php echo e($pesan->penyewa->nama_lengkap); ?> ingin konfirmasi pemesanan Kamar <?php echo e($pesan->kamar->nomor_kamar); ?> di <?php echo e($pesan->kamar->kost->nama_kost); ?>"
                   target="_blank"
                   class="btn" style="background: #25D366;">
                    💬 Hubungi Pemilik
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/pesan/show.blade.php ENDPATH**/ ?>