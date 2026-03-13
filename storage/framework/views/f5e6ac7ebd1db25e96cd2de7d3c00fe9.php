<?php $__env->startSection('title', 'Detail Pesanan #' . $order->id_pesanan_makanan); ?>

<?php $__env->startSection('content'); ?>
<div class="container-full" style="padding: 20px;">
    <div style="margin-bottom: 20px;">
        <a href="<?php echo e(route('orders.index')); ?>" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: 28px; color: #333; margin: 0;">Pesanan #<?php echo e($order->id_pesanan_makanan); ?></h1>
                        <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background: <?php echo e($order->statusBadge); ?>;">
                            <?php echo e($order->statusLabel); ?>

                        </span>
                    </div>
                    <p style="color: #666; font-size: 16px;">🏢 <?php echo e($order->kost->nama_kost); ?></p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php if($order->isPendingPayment()): ?>
                        <a href="<?php echo e(route('orders.pay', $order->id_pesanan_makanan)); ?>" class="btn" style="background: #43e97b;">
                            💳 Bayar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            
            <div style="margin: 30px 0;">
                <h3 style="font-size: 18px; color: #333; margin-bottom: 15px; font-weight: 600;">📦 Item Pesanan</h3>
                <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                    <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="display: flex; justify-content: space-between; align-items: start; padding: 15px 0; border-bottom: <?php echo e($loop->last ? 'none' : '1px solid #e0e0e0'); ?>;">
                            <div style="flex: 1;">
                                <p style="font-size: 15px; color: #333; font-weight: 600; margin: 0;"><?php echo e($detail->makanan->nama_makanan); ?></p>
                                <p style="font-size: 13px; color: #666; margin: 5px 0 0;">
                                    <?php echo e($detail->jumlah); ?> x Rp <?php echo e(number_format($detail->harga_satuan, 0, ',', '.')); ?>

                                </p>
                                <?php if($detail->catatan_item): ?>
                                    <p style="font-size: 12px; color: #999; margin: 5px 0 0;">📝 <?php echo e($detail->catatan_item); ?></p>
                                <?php endif; ?>
                            </div>
                            <strong style="font-size: 15px; color: #970747;"><?php echo e($detail->formatted_subtotal); ?></strong>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📊 Total Item</p>
                    <p style="font-size: 24px; color: #333; font-weight: 700; margin: 0;"><?php echo e($order->total_item); ?> porsi</p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">💰 Total Harga</p>
                    <p style="font-size: 24px; color: #970747; font-weight: 700; margin: 0;"><?php echo e($order->formatted_total_harga); ?></p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <p style="font-size: 13px; color: #666; margin-bottom: 5px;">📅 Tanggal Pesan</p>
                    <p style="font-size: 16px; color: #333; font-weight: 600; margin: 0;"><?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                </div>
            </div>

            <?php if($order->catatan): ?>
                <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <p style="font-size: 14px; color: #856404; margin: 0;">
                        <strong>📝 Catatan:</strong> <?php echo e($order->catatan); ?>

                    </p>
                </div>
            <?php endif; ?>

            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; gap: 10px; flex-wrap: wrap;">
                <?php if($order->canBeCancelled()): ?>
                    <form action="<?php echo e(route('orders.cancel', $order->id_pesanan_makanan)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn" style="background: #f5576c;">
                            🚫 Batalkan Pesanan
                        </button>
                    </form>
                <?php endif; ?>

                <?php if($order->canBeCompleted()): ?>
                    <form action="<?php echo e(route('orders.complete', $order->id_pesanan_makanan)); ?>" method="POST" onsubmit="return confirm('Konfirmasi bahwa pesanan sudah Anda terima?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn" style="background: #43e97b;">
                            ✅ Konfirmasi Diterima
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; color: #999; font-size: 13px; flex-wrap: wrap; gap: 10px;">
                <span>ID Order: <?php echo e($order->orderan_id); ?></span>
                <span>Terakhir diupdate: <?php echo e($order->updated_at->format('d M Y, H:i')); ?></span>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/tenant/orders/show.blade.php ENDPATH**/ ?>