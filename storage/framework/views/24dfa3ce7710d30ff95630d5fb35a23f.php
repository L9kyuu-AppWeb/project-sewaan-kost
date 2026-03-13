<?php $__env->startSection('title', 'Pembayaran Berhasil'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 40px 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center;">
        <div style="width: 80px; height: 80px; background: #43e97b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
            <span style="font-size: 40px; color: white;">✅</span>
        </div>
        
        <h1 style="font-size: 24px; color: #333; margin-bottom: 10px;">Pembayaran Berhasil!</h1>
        <p style="color: #666; font-size: 15px; margin-bottom: 25px;">
            Pesanan makanan Anda sedang diproses
        </p>

        <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #666; font-size: 14px;">Nomor Pesanan</span>
                <strong style="color: #333; font-size: 14px;">#<?php echo e($order->id_pesanan_makanan); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #666; font-size: 14px;">Order ID</span>
                <strong style="color: #333; font-size: 14px;"><?php echo e($order->orderan_id); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #666; font-size: 14px;">Total Dibayar</span>
                <strong style="color: #970747; font-size: 18px; font-weight: 700;"><?php echo e($order->formatted_total_harga); ?></strong>
            </div>
        </div>

        <div style="padding: 15px; background: #e8f5e9; border-radius: 8px; margin-bottom: 25px;">
            <p style="font-size: 14px; color: #2e7d32; margin: 0;">
                🍽️ Pesanan Anda akan segera diproses dan diantar
            </p>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="<?php echo e(route('orders.index')); ?>" class="btn" style="background: #970747; flex: 1; max-width: 200px;">
                Lihat Riwayat Pesanan
            </a>
            <a href="<?php echo e(route('food.index')); ?>" class="btn" style="background: #6c757d; flex: 1; max-width: 200px;">
                Pesan Lagi
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/tenant/orders/payment-success.blade.php ENDPATH**/ ?>