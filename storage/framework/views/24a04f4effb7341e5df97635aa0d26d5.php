<?php $__env->startSection('title', 'Pengaturan Fitur - ' . $kost->nama_kost); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 800px;">
    <div style="margin-bottom: 20px;">
        <a href="<?php echo e(route('kost.index')); ?>" style="color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.9;">
            ← Kembali ke Daftar Kost
        </a>
    </div>

    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: white; margin-bottom: 5px;">⚙️ Pengaturan Fitur</h1>
        <p style="color: rgba(255,255,255,0.9); font-size: 14px;"><?php echo e($kost->nama_kost); ?></p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form method="POST" action="<?php echo e(route('kost.settings.update', $kost->id_kost)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 18px; color: #333; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #970747;">
                    🎯 Aktifkan Fitur Layanan
                </h3>
                <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                    Aktifkan atau nonaktifkan fitur layanan untuk kost Anda. Fitur yang dinonaktifkan tidak akan ditampilkan di menu penyewa.
                </p>

                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 2px solid #e0e0e0; border-radius: 12px; margin-bottom: 15px; transition: all 0.3s;"
                     onmouseover="this.style.borderColor='#970747'; this.style.background='#fce4ec'"
                     onmouseout="this.style.borderColor='#e0e0e0'; this.style.background='white'">
                    <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            🍽️
                        </div>
                        <div>
                            <h4 style="font-size: 16px; color: #333; margin: 0 0 5px 0;">Fitur Makanan</h4>
                            <p style="font-size: 13px; color: #666; margin: 0;">Penyewa dapat memesan makanan dari kost Anda</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                            <input type="checkbox" name="enable_makanan" value="1" <?php echo e($setting->enable_makanan ? 'checked' : ''); ?>

                                   style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;">
                                <span style="position: absolute; content: ''; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                            </span>
                        </label>
                        <input type="hidden" name="enable_makanan" value="<?php echo e($setting->enable_makanan ? '1' : '0'); ?>">
                    </div>
                </div>

                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 2px solid #e0e0e0; border-radius: 12px; margin-bottom: 15px; transition: all 0.3s;"
                     onmouseover="this.style.borderColor='#4facfe'; this.style.background='#e3f2fd'"
                     onmouseout="this.style.borderColor='#e0e0e0'; this.style.background='white'">
                    <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            💧
                        </div>
                        <div>
                            <h4 style="font-size: 16px; color: #333; margin: 0 0 5px 0;">Fitur Galon</h4>
                            <p style="font-size: 13px; color: #666; margin: 0;">Penyewa dapat memesan air galon</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                            <input type="checkbox" name="enable_galon" value="1" <?php echo e($setting->enable_galon ? 'checked' : ''); ?>

                                   style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;">
                                <span style="position: absolute; content: ''; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                            </span>
                        </label>
                        <input type="hidden" name="enable_galon" value="<?php echo e($setting->enable_galon ? '1' : '0'); ?>">
                    </div>
                </div>

                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 2px solid #e0e0e0; border-radius: 12px; margin-bottom: 15px; transition: all 0.3s;"
                     onmouseover="this.style.borderColor='#96fbc4'; this.style.background='#e8f5e9'"
                     onmouseout="this.style.borderColor='#e0e0e0'; this.style.background='white'">
                    <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #96fbc4 0%, #f9f586 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            👕
                        </div>
                        <div>
                            <h4 style="font-size: 16px; color: #333; margin: 0 0 5px 0;">Fitur Laundry</h4>
                            <p style="font-size: 13px; color: #666; margin: 0;">Penyewa dapat memesan layanan laundry</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                            <input type="checkbox" name="enable_laundry" value="1" <?php echo e($setting->enable_laundry ? 'checked' : ''); ?>

                                   style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;">
                                <span style="position: absolute; content: ''; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                            </span>
                        </label>
                        <input type="hidden" name="enable_laundry" value="<?php echo e($setting->enable_laundry ? '1' : '0'); ?>">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="flex: 1; min-width: 200px; background: linear-gradient(135deg, #970747 0%, #c41e6a 100%);">
                    💾 Simpan Pengaturan
                </button>
                <a href="<?php echo e(route('kost.index')); ?>" class="btn" style="background: #6c757d; min-width: 120px; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>

    
    <div style="background: #e3f2fd; border-radius: 12px; padding: 20px; margin-top: 20px; border-left: 4px solid #2196f3;">
        <p style="font-size: 14px; color: #1565c0; margin: 0;">
            ℹ️ <strong>Catatan:</strong> Fitur yang dinonaktifkan tidak akan ditampilkan di menu navigasi penyewa dan tidak dapat diakses melalui URL langsung.
        </p>
    </div>
</div>

<script>
    // Toggle checkbox visual
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const hiddenInput = this.parentElement.nextElementSibling;
            if (hiddenInput && hiddenInput.tagName === 'INPUT') {
                hiddenInput.value = this.checked ? '1' : '0';
            }
            
            // Update toggle visual
            const toggle = this.parentElement.querySelector('span');
            if (toggle) {
                const thumb = toggle.querySelector('span');
                if (this.checked) {
                    toggle.style.backgroundColor = '#970747';
                    if (thumb) thumb.style.transform = 'translateX(26px)';
                } else {
                    toggle.style.backgroundColor = '#ccc';
                    if (thumb) thumb.style.transform = 'translateX(0)';
                }
            }
        });

        // Initialize toggle visual
        const toggle = checkbox.parentElement.querySelector('span');
        const thumb = toggle?.querySelector('span');
        if (checkbox.checked && toggle && thumb) {
            toggle.style.backgroundColor = '#970747';
            thumb.style.transform = 'translateX(26px)';
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/kost/settings/edit.blade.php ENDPATH**/ ?>