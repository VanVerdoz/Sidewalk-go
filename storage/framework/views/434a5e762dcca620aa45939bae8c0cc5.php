<?php $__env->startSection('title', 'Detail Produk'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .detail-container {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        max-width: 800px;
    }

    .detail-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .detail-title {
        font-size: 24px;
        color: #333;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        padding: 15px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .detail-label {
        font-weight: 500;
        color: #666;
    }

    .detail-value {
        color: #333;
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
        margin-top: 20px;
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
        color: #333;
        font-weight: 600;
        margin-bottom: 30px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Detail Produk</h2>

<div class="detail-container">
    <div class="detail-header">
        <h3 class="detail-title"><?php echo e($produk->nama); ?></h3>
    </div>

    <div class="detail-row">
        <div class="detail-label">Harga</div>
        <div class="detail-value">Rp. <?php echo e(number_format($produk->harga, 0, ',', '.')); ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Deskripsi</div>
        <div class="detail-value"><?php echo e($produk->deskripsi ?? '-'); ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Total Stok</div>
        <div class="detail-value"><?php echo e($produk->stok->sum('jumlah')); ?> unit</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value"><?php echo e(ucfirst($produk->status ?? 'aktif')); ?></div>
    </div>

    <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views/produk/show.blade.php ENDPATH**/ ?>