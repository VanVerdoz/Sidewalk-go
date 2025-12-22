<?php $__env->startSection('title', 'Edit Produk'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-container {
        background: var(--surface);
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--shadow-md);
        max-width: 600px;
        border: 1px solid var(--border);
        color: var(--text);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text);
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: var(--surface);
        color: var(--text);
    }

    .form-control:focus {
        outline: none;
        border-color: #ff6b35;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .page-title {
        font-size: 28px;
        color: var(--text);
        font-weight: 600;
        margin-bottom: 30px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Edit Produk</h2>

<div class="form-container">
    <form action="<?php echo e(route('produk.update', $produk->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="form-group">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?php echo e(old('nama_produk', $produk->nama)); ?>" required>
            <?php $__errorArgs = ['nama_produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: red;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="<?php echo e(old('harga', $produk->harga)); ?>" required>
            <?php $__errorArgs = ['harga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: red;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Kategori</label>
            <input type="text" name="kategori" class="form-control" value="<?php echo e(old('kategori', $produk->kategori)); ?>" required>
            <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: red;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="aktif" <?php echo e(old('status', $produk->status) === 'aktif' ? 'selected' : ''); ?>>Tersedia</option>
                <option value="nonaktif" <?php echo e(old('status', $produk->status) === 'nonaktif' ? 'selected' : ''); ?>>Tidak Tersedia</option>
            </select>
            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: red;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"><?php echo e(old('deskripsi', $produk->deskripsi)); ?></textarea>
            <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: red;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update
            </button>
            <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Batal
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\produk\edit.blade.php ENDPATH**/ ?>