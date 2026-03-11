<?php $__env->startSection('title', 'Kelola Pesanan Makanan - Sewa An Kost'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-full" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">📋 Kelola Pesanan Makanan</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 14px;">Kelola semua pesanan makanan dari kost Anda</p>
        </div>
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

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form action="<?php echo e(route('owner.orders.index')); ?>" method="GET" style="display: flex; gap: 15px; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label for="kost_id" style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Filter berdasarkan Kost:</label>
                <select name="kost_id" id="kost_id" onchange="this.form.submit()" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Kost</option>
                    <?php $__currentLoopData = $kosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k->id_kost); ?>" <?php echo e($kostId == $k->id_kost ? 'selected' : ''); ?>>
                            <?php echo e($k->nama_kost); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div style="flex: 1; min-width: 200px;">
                <label for="status" style="display: block; color: #222; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Filter berdasarkan Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_bayar" <?php echo e($status == 'menunggu_bayar' ? 'selected' : ''); ?>>Menunggu Pembayaran</option>
                    <option value="diproses" <?php echo e($status == 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                    <option value="dikirim" <?php echo e($status == 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                    <option value="selesai" <?php echo e($status == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                    <option value="dibatalkan" <?php echo e($status == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                </select>
            </div>
            
            <a href="<?php echo e(route('owner.orders.index')); ?>" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                Reset
            </a>
        </form>
    </div>

    <?php if($orders->count() > 0): ?>
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Tanggal</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Order ID</th>
                        <th style="padding: 15px; text-align: left; font-size: 14px;">Pemesan</th>
                        <th style="padding: 15px; text-align: center; font-size: 14px;">Item</th>
                        <th style="padding: 15px; text-align: right; font-size: 14px;">Total</th>
                        <th style="padding: 15px; text-align: center; font-size: 14px;">Status</th>
                        <th style="padding: 15px; text-align: center; font-size: 14px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-size: 13px; color: #666;">
                                <?php echo e($order->created_at->format('d M Y, H:i')); ?>

                            </td>
                            <td style="padding: 15px; font-size: 12px; color: #333;">
                                <strong><?php echo e($order->orderan_id); ?></strong>
                            </td>
                            <td style="padding: 15px; font-size: 14px; color: #333;">
                                <strong><?php echo e($order->penyewa->nama_lengkap ?? 'N/A'); ?></strong>
                                <p style="font-size: 11px; color: #999; margin: 3px 0 0;"><?php echo e($order->penyewa->email ?? ''); ?></p>
                            </td>
                            <td style="padding: 15px; text-align: center; font-size: 14px; color: #333;">
                                <?php echo e($order->total_item); ?>

                            </td>
                            <td style="padding: 15px; text-align: right; font-size: 14px; font-weight: 600; color: #970747;">
                                <?php echo e($order->formatted_total_harga); ?>

                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; color: white; background: <?php echo e($order->statusBadge); ?>;">
                                    <?php echo e($order->statusLabel); ?>

                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;">
                                    <a href="<?php echo e(route('owner.orders.show', $order->id_pesanan_makanan)); ?>" 
                                       style="padding: 6px 10px; background: #970747; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        👁️
                                    </a>
                                    
                                    <?php if($order->status_antar == 'menunggu_bayar'): ?>
                                        <form action="<?php echo e(route('owner.orders.process', $order->id_pesanan_makanan)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Tandai pesanan ini sebagai diproses?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" style="padding: 6px 10px; background: #4facfe; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                ⚙️
                                            </button>
                                        </form>
                                    <?php elseif($order->status_antar == 'diproses'): ?>
                                        <form action="<?php echo e(route('owner.orders.deliver', $order->id_pesanan_makanan)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Tandai pesanan ini sebagai dikirim?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" style="padding: 6px 10px; background: #43e97b; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                🚚
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if(in_array($order->status_antar, ['menunggu_bayar', 'diproses'])): ?>
                                        <form action="<?php echo e(route('owner.orders.cancel', $order->id_pesanan_makanan)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Batalkan pesanan ini?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" style="padding: 6px 10px; background: #f5576c; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                🚫
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            <?php echo e($orders->links()); ?>

        </div>
    <?php else: ?>
        <div style="background: white; border-radius: 12px; padding: 60px 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <span style="font-size: 60px; display: block; margin-bottom: 20px;">📋</span>
            <h3 style="color: #970747; margin-bottom: 10px;">Belum Ada Pesanan</h3>
            <p style="color: #666;">Belum ada pesanan makanan untuk kost Anda.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/owner/orders/index.blade.php ENDPATH**/ ?>