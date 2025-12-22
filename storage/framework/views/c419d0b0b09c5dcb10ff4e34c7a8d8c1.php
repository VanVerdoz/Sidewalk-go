<?php $__env->startSection('title', 'Profil Akun'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 14px; }
    .profile-card { background: var(--surface); border-radius: 16px; box-shadow: var(--shadow-sm); padding: 20px; border: 1px solid var(--border); }
    .form-group { margin-bottom: 14px; }
    .form-label { display:block; font-size:13px; font-weight:600; color:#444; margin-bottom:6px; }
    .form-control { width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:12px; }
    .form-actions { display:flex; gap:10px; margin-top:16px; }
    .readonly { background: var(--table-hover); color: var(--muted); }
    .hint { font-size:12px; color: var(--muted); margin-top:4px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Profil Akun</h2>

<div class="profile-card">
    <form action="<?php echo e(route('profile.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo e(old('username', $user->username)); ?>" required>
            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="hint" style="color:red;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?php echo e(old('nama_lengkap', $user->nama_lengkap)); ?>" required>
            <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="hint" style="color:red;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Password Baru</label>
            <div style="display:flex; align-items:center; gap:8px;">
                <input type="password" id="pwd" name="password" class="form-control" placeholder="Kosongkan jika tidak mengubah" style="flex:1;">
                <button type="button" class="btn btn-secondary btn-small" id="togglePwd" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="hint">Minimal 6 karakter</div>
        </div>

        <div class="form-group" style="display:flex; gap:10px;">
            <button type="button" class="btn btn-secondary btn-small" onclick="resetPasswordField()">
                <i class="fas fa-redo"></i> Reset Password
            </button>
            <button type="button" class="btn btn-secondary btn-small" onclick="generatePassword()">
                <i class="fas fa-key"></i> Generate Password
            </button>
        </div>

        <div class="form-group">
            <label class="form-label">Peran</label>
            <input type="text" class="form-control readonly" value="<?php echo e(ucfirst(str_replace('_',' ', $user->role))); ?>" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <input type="text" class="form-control readonly" value="<?php echo e($user->status ?? 'active'); ?>" readonly>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function resetPasswordField(){
        var el = document.getElementById('pwd');
        if(el){ el.value = ''; el.focus(); }
    }
    function generatePassword(){
        var el = document.getElementById('pwd');
        if(!el) return;
        var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789@#$%';
        var pwd = '';
        for (var i = 0; i < 10; i++) { pwd += chars[Math.floor(Math.random() * chars.length)]; }
        el.value = pwd;
        el.type = 'text';
        var icon = document.querySelector('#togglePwd i');
        if(icon){ icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
    }
    function togglePassword(){
        var el = document.getElementById('pwd');
        var icon = document.querySelector('#togglePwd i');
        if(!el || !icon) return;
        if(el.type === 'password'){
            el.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            el.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
        el.focus();
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\profile\index.blade.php ENDPATH**/ ?>