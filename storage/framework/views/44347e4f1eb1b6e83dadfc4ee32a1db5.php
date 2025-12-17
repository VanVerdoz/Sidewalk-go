<?php $__env->startSection('title', 'Detail Laporan Keuangan'); ?>

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

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .detail-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        color: var(--text);
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 16px;
        color: var(--text);
        font-weight: 500;
    }

    .highlight-box {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        margin-top: 20px;
    }

    .highlight-label {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 10px;
    }

    .highlight-value {
        font-size: 32px;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2 class="page-title">Detail Laporan Keuangan</h2>
    <a href="<?php echo e(route('laporan-keuangan.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="detail-container">
    <!-- Informasi Laporan -->
    <div class="detail-section">
        <h3 class="detail-section-title">Informasi Laporan</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">ID Laporan</span>
                <span class="detail-value"><?php echo e($laporan->id); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Cabang</span>
                <span class="detail-value"><?php echo e($laporan->cabang->nama_cabang ?? '-'); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Periode Awal</span>
                <span class="detail-value"><?php echo e(\Carbon\Carbon::parse($laporan->periode_awal)->format('d F Y')); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Periode Akhir</span>
                <span class="detail-value"><?php echo e(\Carbon\Carbon::parse($laporan->periode_akhir)->format('d F Y')); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Dibuat Pada</span>
                <span class="detail-value"><?php echo e(\Carbon\Carbon::parse($laporan->created_at)->format('d F Y H:i')); ?></span>
            </div>
        </div>

        <!-- Total Pendapatan Highlight -->
        <div class="highlight-box">
            <div class="highlight-label">Total Pendapatan</div>
            <div class="highlight-value">Rp. <?php echo e(number_format($laporan->total_pendapatan, 0, ',', '.')); ?></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\laporan-keuangan\show.blade.php ENDPATH**/ ?>