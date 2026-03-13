<?php $__env->startSection('title', 'Pembayaran Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 600px;">
    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h1 style="font-size: 24px; color: #333; margin-bottom: 10px;">💳 Pembayaran</h1>
            <p style="color: #666;">Pesanan #<?php echo e($order->id_pesanan_makanan); ?></p>
        </div>

        <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
            <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 600;">📝 Detail Pembayaran</h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0;">
                <span style="color: #666; font-size: 14px;">Order ID</span>
                <strong style="color: #333; font-size: 14px;"><?php echo e($order->orderan_id); ?></strong>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0;">
                <span style="color: #666; font-size: 14px;">Total Item</span>
                <strong style="color: #333; font-size: 14px;"><?php echo e($order->total_item); ?> porsi</strong>
            </div>
            
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #666; font-size: 14px; font-weight: 600;">Total Pembayaran</span>
                <strong style="color: #970747; font-size: 20px; font-weight: 700;"><?php echo e($pembayaran->formatted_jumlah_bayar); ?></strong>
            </div>
        </div>

        <div id="snap-container" style="text-align: center; padding: 20px 0;"></div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="<?php echo e(route('orders.show', $order->id_pesanan_makanan)); ?>" style="color: #666; text-decoration: none; font-size: 14px; font-weight: 600;">
                ← Kembali ke Detail Pesanan
            </a>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        snap.pay('<?php echo e($snapToken); ?>', {
            onSuccess: function(result) {
                window.location.href = '<?php echo e(route("orders.payment.success", $order->id_pesanan_makanan)); ?>';
            },
            onPending: function(result) {
                window.location.href = '<?php echo e(route("orders.show", $order->id_pesanan_makanan)); ?>';
            },
            onError: function(error) {
                window.location.href = '<?php echo e(route("orders.payment.failed", $order->id_pesanan_makanan)); ?>';
            },
            onClose: function() {
                window.location.href = '<?php echo e(route("orders.show", $order->id_pesanan_makanan)); ?>';
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/tenant/orders/payment.blade.php ENDPATH**/ ?>