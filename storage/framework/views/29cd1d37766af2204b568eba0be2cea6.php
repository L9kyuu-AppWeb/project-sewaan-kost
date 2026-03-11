<?php $__env->startSection('title', 'Sewa An Kost - Sistem Manajemen Sewa Kost Modern'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); padding: 80px 20px 60px; margin-bottom: 40px;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center; color: white;">
        <span style="font-size: 80px; display: block; margin-bottom: 20px; animation: bounce 2s infinite;">🏠</span>
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
            Sewa An Kost
        </h1>
        <p style="font-size: 20px; opacity: 0.95; max-width: 700px; margin: 0 auto 30px; line-height: 1.6;">
            Platform Manajemen Sewa Kost Terpadu<br>
            Kelola properti, pesanan makanan, galon, dan laundry dalam satu tempat
        </p>
        
        <?php if(auth()->guard()->guest()): ?>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo e(route('kost-public.index')); ?>" class="btn" style="background: white; color: #970747; font-size: 16px; padding: 16px 32px; min-width: 180px;">
                    🔍 Cari Kost Sekarang
                </a>
                <a href="<?php echo e(route('register')); ?>" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; font-size: 16px; padding: 16px 32px; min-width: 180px;">
                    📝 Daftar Gratis
                </a>
            </div>
        <?php else: ?>
            <div style="margin-top: 30px;">
                <a href="<?php echo e(auth()->user()->role === 'pemilik' ? route('dashboard.pemilik') : route('dashboard.penyewa')); ?>" 
                   class="btn" style="background: white; color: #970747; font-size: 18px; padding: 16px 40px; min-width: 220px;">
                    📊 Buka Dashboard
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Features Section -->
<div style="max-width: 1200px; margin: 0 auto; padding: 0 20px 60px;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 36px; color: #222; margin-bottom: 15px; font-weight: 700;">
            Fitur Lengkap untuk Kebutuhan Kost Anda
        </h2>
        <p style="color: #666; font-size: 18px; max-width: 600px; margin: 0 auto;">
            Semua yang Anda butuhkan untuk mengelola dan menyewa kost dalam satu platform
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
        <!-- Feature 1: Kamar -->
        <div style="background: white; border-radius: 16px; padding: 35px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 36px;">🛏️</span>
            </div>
            <h3 style="font-size: 22px; color: #222; margin-bottom: 12px; font-weight: 700;">Sewa Kamar</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                Cari dan pesan kamar kost dengan mudah. Kelola okupansi dan pembayaran untuk pemilik kost.
            </p>
            <ul style="text-align: left; color: #666; font-size: 14px; line-height: 2; padding-left: 20px;">
                <li>✅ Pencarian kost berbasis lokasi</li>
                <li>✅ Pembayaran online via Midtrans</li>
                <li>✅ Manajemen kontrak otomatis</li>
            </ul>
        </div>

        <!-- Feature 2: Makanan -->
        <div style="background: white; border-radius: 16px; padding: 35px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 36px;">🍽️</span>
            </div>
            <h3 style="font-size: 22px; color: #222; margin-bottom: 12px; font-weight: 700;">Pesanan Makanan</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                Pesan makanan dari kost dengan sistem keranjang. Bayar multiple items sekaligus.
            </p>
            <ul style="text-align: left; color: #666; font-size: 14px; line-height: 2; padding-left: 20px;">
                <li>✅ Multiple items dalam satu pesanan</li>
                <li>✅ Pembayaran terintegrasi</li>
                <li>✅ Tracking status pesanan</li>
            </ul>
        </div>

        <!-- Feature 3: Galon -->
        <div style="background: white; border-radius: 16px; padding: 35px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 36px;">💧</span>
            </div>
            <h3 style="font-size: 22px; color: #222; margin-bottom: 12px; font-weight: 700;">Pesanan Galon</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                Pesan air galon dengan upload foto galon kosong. Owner antar dan jemput.
            </p>
            <ul style="text-align: left; color: #666; font-size: 14px; line-height: 2; padding-left: 20px;">
                <li>✅ Upload foto galon kosong</li>
                <li>✅ Tracking pengiriman</li>
                <li>✅ Foto bukti pengantaran</li>
            </ul>
        </div>

        <!-- Feature 4: Laundry -->
        <div style="background: white; border-radius: 16px; padding: 35px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 36px;">👕</span>
            </div>
            <h3 style="font-size: 22px; color: #222; margin-bottom: 12px; font-weight: 700;">Laundry</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                Layanan laundry dengan penimbangan dan estimasi waktu selesai yang jelas.
            </p>
            <ul style="text-align: left; color: #666; font-size: 14px; line-height: 2; padding-left: 20px;">
                <li>✅ Input berat pakaian</li>
                <li>✅ Estimasi waktu selesai</li>
                <li>✅ Audit keterlambatan</li>
            </ul>
        </div>

        <!-- Feature 5: Dashboard Pemilik -->
        <div style="background: white; border-radius: 16px; padding: 35px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 36px;">📊</span>
            </div>
            <h3 style="font-size: 22px; color: #222; margin-bottom: 12px; font-weight: 700;">Dashboard Pemilik</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                Pantau semua pendapatan dan pesanan dalam satu dashboard terpusat.
            </p>
            <ul style="text-align: left; color: #666; font-size: 14px; line-height: 2; padding-left: 20px;">
                <li>✅ Statistik pendapatan per layanan</li>
                <li>✅ Manajemen pesanan terpusat</li>
                <li>✅ Kontrol fitur per kost</li>
            </ul>
        </div>

        <!-- Feature 6: Pembayaran -->
        <div style="background: white; border-radius: 16px; padding: 35px 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 36px;">💳</span>
            </div>
            <h3 style="font-size: 22px; color: #222; margin-bottom: 12px; font-weight: 700;">Pembayaran Digital</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">
                Integrasi dengan Midtrans untuk pembayaran yang aman dan terpercaya.
            </p>
            <ul style="text-align: left; color: #666; font-size: 14px; line-height: 2; padding-left: 20px;">
                <li>✅ Transfer Bank, E-Wallet, QRIS</li>
                <li>✅ Auto-verifikasi pembayaran</li>
                <li>✅ Tracking status real-time</li>
            </ul>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 60px 20px; margin-bottom: 40px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 32px; color: #222; margin-bottom: 15px; font-weight: 700;">
                Kenapa Memilih Sewa An Kost?
            </h2>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center;">
            <div>
                <div style="font-size: 48px; margin-bottom: 10px;">🏢</div>
                <div style="font-size: 36px; font-weight: 800; color: #970747; margin-bottom: 8px;">Multi-Properti</div>
                <div style="color: #666; font-size: 15px;">Kelola banyak kost dalam satu akun</div>
            </div>
            <div>
                <div style="font-size: 48px; margin-bottom: 10px;">🔄</div>
                <div style="font-size: 36px; font-weight: 800; color: #4facfe; margin-bottom: 8px;">Real-Time</div>
                <div style="color: #666; font-size: 15px;">Update status otomatis</div>
            </div>
            <div>
                <div style="font-size: 48px; margin-bottom: 10px;">🔒</div>
                <div style="font-size: 36px; font-weight: 800; color: #43e97b; margin-bottom: 8px;">Aman</div>
                <div style="color: #666; font-size: 15px;">Pembayaran terenkripsi</div>
            </div>
            <div>
                <div style="font-size: 48px; margin-bottom: 10px;">📱</div>
                <div style="font-size: 36px; font-weight: 800; color: #ffa751; margin-bottom: 8px;">Responsive</div>
                <div style="color: #666; font-size: 15px;">Akses dari semua device</div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<?php if(auth()->guard()->guest()): ?>
<div style="max-width: 800px; margin: 0 auto; padding: 0 20px 60px; text-align: center;">
    <div style="background: linear-gradient(135deg, #970747 0%, #c41e6a 100%); border-radius: 20px; padding: 50px 30px; box-shadow: 0 10px 40px rgba(151,7,71,0.3);">
        <h2 style="font-size: 32px; color: white; margin-bottom: 15px; font-weight: 700;">
            Siap untuk Memulai?
        </h2>
        <p style="color: rgba(255,255,255,0.9); font-size: 18px; margin-bottom: 30px; line-height: 1.6;">
            Bergabunglah dengan ratusan pemilik kost dan penyewa yang telah menggunakan platform kami
        </p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo e(route('register')); ?>" class="btn" style="background: white; color: #970747; font-size: 16px; padding: 16px 32px; min-width: 180px;">
                🚀 Daftar Sekarang - Gratis!
            </a>
            <a href="<?php echo e(route('login')); ?>" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; font-size: 16px; padding: 16px 32px; min-width: 150px;">
                🔐 Login
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Footer -->
<div style="background: #222; color: white; padding: 40px 20px; margin-top: 40px;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
        <div style="margin-bottom: 20px;">
            <span style="font-size: 40px; display: block; margin-bottom: 10px;">🏠</span>
            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 10px;">Sewa An Kost</h3>
            <p style="color: rgba(255,255,255,0.7); font-size: 15px; max-width: 500px; margin: 0 auto;">
                Platform manajemen sewa kost terpadu untuk pemilik dan penyewa di Indonesia
            </p>
        </div>
        
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; margin-top: 30px;">
            <p style="color: rgba(255,255,255,0.5); font-size: 14px;">
                © 2026 Sewa An Kost. All rights reserved.
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-20px); }
        60% { transform: translateY(-10px); }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/home.blade.php ENDPATH**/ ?>