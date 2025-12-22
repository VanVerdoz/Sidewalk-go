<?php $__env->startSection('title', 'Edit Stok'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-container {
        background: var(--surface);
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--shadow-md);
        max-width: 100%;
        width: 100%;
        min-height: calc(100vh - 160px);
        padding-bottom: 160px;
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
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
    }
    
    .form-control:disabled {
        background-color: #e9ecef;
        opacity: 1;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    @media (max-width: 768px) {
        .form-container { padding: 20px; border-radius: 16px; min-height: auto; padding-bottom: 80px; }
        .form-actions { flex-direction: column; gap: 8px; }
        .form-actions .btn { display: block; width: 100%; }
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
<h2 class="page-title">Edit Stok</h2>

<div class="form-container">
    <form action="<?php echo e(route('stok.update', $stok->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="form-group">
            <label class="form-label">Cabang</label>
            <input type="text" class="form-control" value="<?php echo e($stok->cabang->nama_cabang); ?>" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">Produk</label>
            <input type="text" class="form-control" value="<?php echo e($stok->produk->nama_produk); ?>" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="<?php echo e(old('jumlah', $stok->jumlah)); ?>" min="0" required>
            <?php $__errorArgs = ['jumlah'];
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
                Simpan Perubahan
            </button>
            <a href="<?php echo e(route('stok.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Batal
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\stok\edit.blade.php ENDPATH**/ ?>