<?php $__env->startSection('title', 'Detail Transaksi'); ?>

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

    .table-container {
        background: var(--surface);
        border-radius: 15px;
        overflow: hidden;
        margin-top: 20px;
        border: 1px solid var(--border);
        color: var(--text);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
    }

    .table thead th {
        padding: 15px;
        text-align: left;
        font-weight: 500;
        font-size: 14px;
    }

    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background: var(--table-hover);
    }

    .table tbody td {
        padding: 15px;
        font-size: 14px;
        color: var(--text);
    }

    .total-section {
        margin-top: 20px;
        padding: 20px;
        background: var(--surface);
        border-radius: 12px;
        text-align: right;
        border: 1px solid var(--border);
    }

    .total-label {
        font-size: 18px;
        color: var(--text);
        font-weight: 600;
    }

    .total-value {
        font-size: 24px;
        color: #ff6b35;
        font-weight: bold;
        margin-top: 5px;
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
    <h2 class="page-title">Detail Transaksi</h2>
    <a href="<?php echo e(route('penjualan.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="detail-container">
    <!-- Informasi Transaksi -->
    <div class="detail-section">
        <h3 class="detail-section-title">Informasi Transaksi</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">ID Transaksi</span>
                <span class="detail-value"><?php echo e($penjualan->id); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal</span>
                <span class="detail-value"><?php echo e(\Carbon\Carbon::parse($penjualan->tanggal)->format('d F Y')); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Cabang</span>
                <span class="detail-value"><?php echo e($penjualan->cabang->nama_cabang ?? '-'); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Raider</span>
                <span class="detail-value"><?php echo e($penjualan->pengguna->nama_lengkap ?? $penjualan->pengguna->username ?? '-'); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Metode Pembayaran</span>
                <span class="detail-value"><?php echo e(ucfirst($penjualan->metode_pembayaran)); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Keterangan</span>
                <span class="detail-value"><?php echo e($penjualan->keterangan ?? '-'); ?></span>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="detail-section">
        <h3 class="detail-section-title">Detail Produk</h3>
        <?php if($penjualan->detail_penjualan && $penjualan->detail_penjualan->count() > 0): ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $penjualan->detail_penjualan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td data-label="No"><?php echo e($index + 1); ?></td>
                        <td data-label="Nama Produk"><?php echo e($detail->produk->nama_produk ?? '-'); ?></td>
                        <td data-label="Harga Satuan">Rp. <?php echo e(number_format($detail->harga, 0, ',', '.')); ?></td>
                        <td data-label="Jumlah"><?php echo e($detail->jumlah); ?></td>
                        <td data-label="Subtotal">Rp. <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: #999; padding: 20px;">Tidak ada detail produk</p>
        <?php endif; ?>

        <!-- Total -->
        <div class="total-section">
            <div class="total-label">Total Pembayaran</div>
            <div class="total-value">Rp. <?php echo e(number_format($penjualan->total, 0, ',', '.')); ?></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views/penjualan/show.blade.php ENDPATH**/ ?>