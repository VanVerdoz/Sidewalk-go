<?php $__env->startSection('title', 'Edit Transaksi Penjualan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-container {
        background: var(--surface);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        max-width: 720px;
        border: 1px solid var(--border);
        color: var(--text);
    }
    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--text);
    }
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px;
        color: var(--text);
    }
    .form-control, .form-select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        font-size: 14px;
        background: var(--surface);
        color: var(--text);
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #ff6b35;
        box-shadow: 0 0 0 2px rgba(255,107,53,0.15);
    }
    .form-text {
        font-size: 12px;
        color: var(--muted);
    }
    .form-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }
    .btn {
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(135deg,#ff6b35,#f7931e);
        color: #fff;
    }
    .btn-secondary {
        background: #6c757d;
        color: #fff;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Edit Transaksi Penjualan</h2>

<div class="form-container">
    <form action="<?php echo e(route('penjualan.update', $penjualan->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="form-group">
            <label class="form-label">Cabang</label>
            <select name="cabang_id" class="form-select" required>
                <option value="">-- Pilih Cabang --</option>
                <?php $__currentLoopData = $cabang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cb->id); ?>" <?php echo e(old('cabang_id', $penjualan->cabang_id) == $cb->id ? 'selected' : ''); ?>>
                        <?php echo e($cb->nama_cabang ?? 'Cabang '.$cb->id); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal" class="form-control" value="<?php echo e(old('tanggal', \Carbon\Carbon::parse($penjualan->tanggal)->format('Y-m-d'))); ?>" required>
            <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <?php $metode = old('metode_pembayaran', $penjualan->metode_pembayaran); ?>
                <option value="tunai" <?php echo e($metode=='tunai' ? 'selected' : ''); ?>>Tunai</option>
                <option value="transfer" <?php echo e($metode=='transfer' ? 'selected' : ''); ?>>Transfer</option>
                <option value="qris" <?php echo e($metode=='qris' ? 'selected' : ''); ?>>QRIS</option>
            </select>
            <?php $__errorArgs = ['metode_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Total Pembayaran (Rp)</label>
            <input type="number" name="total" class="form-control" value="<?php echo e(old('total', $penjualan->total)); ?>" min="0" step="100" required>
            <?php $__errorArgs = ['total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3"><?php echo e(old('keterangan', $penjualan->keterangan)); ?></textarea>
            <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update Transaksi
            </button>
            <a href="<?php echo e(route('penjualan.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\penjualan\edit.blade.php ENDPATH**/ ?>