<?php $__env->startSection('title', 'Register - Sewa An Kost'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <div class="container" style="max-width: 550px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 50px; display: block; margin-bottom: 10px;">📝</span>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 5px;">Register</h1>
            <p style="color: #666; font-size: 14px;">Buat akun baru</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if($errors->has('role')): ?>
            <div class="alert alert-danger" style="margin-bottom: 15px;">
                <?php echo e($errors->first('role')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" required autofocus placeholder="Sesuai KTP">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="email@contoh.com">
                </div>

                <div class="form-group">
                    <label for="no_hp">No. HP / WhatsApp</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?php echo e(old('no_hp')); ?>" required placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="form-group">
                <label style="display: block; color: #222; font-weight: 600; margin-bottom: 10px; font-size: 14px;">Role *</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label style="background: #f8f9fa; border: 2px solid #ddd; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s; display: block;"
                           onmouseover="if(!document.getElementById('role_pemilik').checked) { this.style.borderColor='#970747'; this.style.boxShadow='0 2px 8px rgba(151,7,71,0.2)'; }"
                           onmouseout="if(!document.getElementById('role_pemilik').checked) { this.style.borderColor='#ddd'; this.style.boxShadow='none'; }">
                        <input type="radio" id="role_pemilik" name="role" value="pemilik" <?php echo e(old('role') === 'pemilik' ? 'checked' : ''); ?> style="display: none;" onchange="updateRoleStyle(this)">
                        <div style="text-align: center;">
                            <span style="font-size: 40px; display: block; margin-bottom: 10px;">🏢</span>
                            <p style="font-size: 14px; font-weight: 700; color: #222; margin: 0;">Pemilik Kost</p>
                            <p style="font-size: 11px; color: #666; margin: 5px 0 0;">Kelola properti</p>
                        </div>
                    </label>

                    <label style="background: #f8f9fa; border: 2px solid #ddd; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s; display: block;"
                           onmouseover="if(!document.getElementById('role_penyewa').checked) { this.style.borderColor='#970747'; this.style.boxShadow='0 2px 8px rgba(151,7,71,0.2)'; }"
                           onmouseout="if(!document.getElementById('role_penyewa').checked) { this.style.borderColor='#ddd'; this.style.boxShadow='none'; }">
                        <input type="radio" id="role_penyewa" name="role" value="penyewa" <?php echo e(old('role') === 'penyewa' ? 'checked' : ''); ?> style="display: none;" onchange="updateRoleStyle(this)">
                        <div style="text-align: center;">
                            <span style="font-size: 40px; display: block; margin-bottom: 10px;">🏠</span>
                            <p style="font-size: 14px; font-weight: 700; color: #222; margin: 0;">Penyewa</p>
                            <p style="font-size: 11px; color: #666; margin: 5px 0 0;">Sewa kamar</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group" id="nik-group" style="display: none;">
                <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                <input type="text" id="nik" name="nik" value="<?php echo e(old('nik')); ?>" maxlength="16" placeholder="16 digit NIK">
            </div>

            <div class="form-group">
                <label for="alamat_asal">Alamat Asal</label>
                <textarea id="alamat_asal" name="alamat_asal" placeholder="Alamat asal Anda"><?php echo e(old('alamat_asal')); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="8" placeholder="Min 8 karakter">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Register</button>
        </form>

        <div class="text-center mt-20">
            <p style="color: #666; font-size: 14px;">
                Sudah punya akun?
                <a href="<?php echo e(route('login')); ?>" class="link">Login disini</a>
            </p>
        </div>
    </div>
</div>

<script>
    function toggleNikField() {
        const rolePemilik = document.getElementById('role_pemilik');
        const rolePenyewa = document.getElementById('role_penyewa');
        const nikGroup = document.getElementById('nik-group');
        const nikInput = document.getElementById('nik');
        
        if (rolePenyewa.checked) {
            nikGroup.style.display = 'block';
            nikInput.required = true;
        } else {
            nikGroup.style.display = 'none';
            nikInput.required = false;
        }
    }

    function updateRoleStyle(radio) {
        // Remove active style from all labels
        document.querySelectorAll('input[name="role"]').forEach(function(r) {
            r.parentElement.style.borderColor = '#ddd';
            r.parentElement.style.boxShadow = 'none';
            r.parentElement.style.background = '#f8f9fa';
        });

        // Add active style to selected label
        if (radio.checked) {
            radio.parentElement.style.borderColor = '#970747';
            radio.parentElement.style.boxShadow = '0 4px 12px rgba(151,7,71,0.3)';
            radio.parentElement.style.background = 'linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%)';
        }

        toggleNikField();
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        const rolePemilik = document.getElementById('role_pemilik');
        const rolePenyewa = document.getElementById('role_penyewa');
        
        if (rolePemilik.checked || rolePenyewa.checked) {
            updateRoleStyle(rolePemilik.checked ? rolePemilik : rolePenyewa);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/auth/register.blade.php ENDPATH**/ ?>