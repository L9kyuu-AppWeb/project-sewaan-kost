<?php $__env->startSection('title', 'Login - Sewa An Kost'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <div class="container" style="max-width: 450px; padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 50px; display: block; margin-bottom: 10px;">🔐</span>
            <h1 style="font-size: 24px; color: #222; margin-bottom: 5px;">Login</h1>
            <p style="color: #666; font-size: 14px;">Masuk ke akun Anda</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus placeholder="Masukkan email Anda">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required placeholder="Masukkan password Anda" style="padding-right: 45px;">
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; color: #666; font-size: 18px;">
                        👁️
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: normal; font-size: 13px; cursor: pointer;">
                    <input type="checkbox" id="remember" name="remember" style="margin: 0; width: 16px; height: 16px; accent-color: #970747;">
                    <span style="color: #666;">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>

        <div class="divider">
            <span>ATAU</span>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p style="font-size: 13px; color: #333; margin-bottom: 10px; font-weight: 600;">📌 Akun Demo (Klik untuk isi otomatis):</p>
            
            <div style="display: grid; gap: 10px;">
                <div class="demo-account" style="background: white; padding: 12px; border-radius: 6px; border: 1px solid #e0e0e0; cursor: pointer; transition: all 0.2s;" 
                     onclick="fillLogin('pemilik@example.com', 'password123')"
                     onmouseover="this.style.borderColor='#970747'; this.style.boxShadow='0 2px 8px rgba(151,7,71,0.2)'"
                     onmouseout="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 20px;">🏢</span>
                        <div style="flex: 1;">
                            <p style="font-size: 13px; font-weight: 600; color: #333; margin: 0;">Pemilik Kost</p>
                            <p style="font-size: 11px; color: #666; margin: 2px 0;">pemilik@example.com</p>
                        </div>
                        <span style="font-size: 11px; color: #970747; font-weight: 600;">KLIK →</span>
                    </div>
                </div>
                
                <div class="demo-account" style="background: white; padding: 12px; border-radius: 6px; border: 1px solid #e0e0e0; cursor: pointer; transition: all 0.2s;" 
                     onclick="fillLogin('penyewa@example.com', 'password123')"
                     onmouseover="this.style.borderColor='#970747'; this.style.boxShadow='0 2px 8px rgba(151,7,71,0.2)'"
                     onmouseout="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 20px;">🏠</span>
                        <div style="flex: 1;">
                            <p style="font-size: 13px; font-weight: 600; color: #333; margin: 0;">Penyewa</p>
                            <p style="font-size: 11px; color: #666; margin: 2px 0;">penyewa@example.com</p>
                        </div>
                        <span style="font-size: 11px; color: #970747; font-weight: 600;">KLIK →</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-20">
            <p style="color: #666; font-size: 14px;">
                Belum punya akun?
                <a href="<?php echo e(route('register')); ?>" class="link">Register disini</a>
            </p>
        </div>
    </div>
</div>

<script>
    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;

        // Add visual feedback
        document.getElementById('email').style.borderColor = '#43e97b';
        document.getElementById('password').style.borderColor = '#43e97b';

        setTimeout(() => {
            document.getElementById('email').style.borderColor = '#ddd';
            document.getElementById('password').style.borderColor = '#ddd';
        }, 500);
    }

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = event.currentTarget;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.innerHTML = '🙈';
        } else {
            passwordInput.type = 'password';
            toggleBtn.innerHTML = '👁️';
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel-sewaan-kost\resources\views/auth/login.blade.php ENDPATH**/ ?>