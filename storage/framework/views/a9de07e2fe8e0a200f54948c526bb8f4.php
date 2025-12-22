<?php $__env->startSection('title', 'Tambah Laporan Keuangan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        color: var(--text);
        font-weight: 600;
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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .form-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
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
        font-size: 14px;
        font-weight: 500;
        color: var(--text);
        margin-bottom: 8px;
    }

    .form-label .required {
        color: #ff6b35;
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

    .form-control.error {
        border-color: #f44336;
    }

    .error-message {
        color: #f44336;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .form-actions { flex-direction: column; gap: 8px; }
        .form-actions .btn { display: block; width: 100%; }
        .form-container { padding: 20px; border-radius: 16px; min-height: auto; padding-bottom: 80px; }
    }

    select.form-control { cursor: pointer; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2 class="page-title">Tambah Laporan Keuangan</h2>
    <a href="<?php echo e(route('laporan-keuangan.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="form-container">
    <form action="<?php echo e(route('laporan-keuangan.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label class="form-label">
                Cabang <span class="required">*</span>
            </label>
            <select name="cabang_id" class="form-control <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <option value="">Pilih Cabang</option>
                <?php $__currentLoopData = $cabang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($item->id); ?>" <?php echo e(old('cabang_id') == $item->id ? 'selected' : ''); ?>>
                    <?php echo e($item->nama_cabang); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="error-message"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">
                Periode Awal <span class="required">*</span>
            </label>
            <input type="date" name="periode_awal" class="form-control <?php $__errorArgs = ['periode_awal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   value="<?php echo e(old('periode_awal')); ?>" required>
            <?php $__errorArgs = ['periode_awal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="error-message"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">
                Periode Akhir <span class="required">*</span>
            </label>
            <input type="date" name="periode_akhir" class="form-control <?php $__errorArgs = ['periode_akhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('periode_akhir')); ?>" required>
            <?php $__errorArgs = ['periode_akhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="error-message"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">
                Total Pendapatan <span class="required">*</span>
            </label>
            <input type="number" name="total_pendapatan" class="form-control <?php $__errorArgs = ['total_pendapatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('total_pendapatan')); ?>" step="0.01" required>
            <?php $__errorArgs = ['total_pendapatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="error-message"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Simpan
            </button>
            <a href="<?php echo e(route('laporan-keuangan.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Batal
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\laporan-keuangan\create.blade.php ENDPATH**/ ?>