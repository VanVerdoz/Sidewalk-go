<?php $__env->startSection('title', 'Stok Produk'); ?>

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

    .btn-sm {
        padding: 8px 15px;
        font-size: 13px;
    }

    .btn-edit {
        background: #4CAF50;
        color: white;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .table-container {
        background: var(--surface);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-md);
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

    .table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s;
    }

    .table tbody tr:hover {
        background: var(--table-hover);
    }

    .table tbody td {
        padding: 15px;
        font-size: 14px;
        color: var(--text);
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 14px;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('styles'); ?>
<style>
    @media (max-width: 640px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        .page-title { font-size: 22px; }
        .btn { padding: 8px 12px; font-size: 13px; border-radius: 10px; }
        .btn-sm { padding: 6px 10px; font-size: 12px; }
        .action-buttons { gap: 6px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2 class="page-title">Stok Produk</h2>
    <?php if(in_array(session('user.role'), ['kepala_gudang', 'owner'])): ?>
    <a href="<?php echo e(route('produk.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Produk
    </a>
    <?php endif; ?>
</div>

<div class="table-container">
    <?php if($produk->count() > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $produk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td data-label="No"><?php echo e($index + 1); ?></td>
                <td data-label="Nama Produk"><?php echo e($item->nama_produk); ?></td>
                <td data-label="Kategori"><?php echo e($item->kategori ?? '-'); ?></td>
                <td data-label="Harga">Rp. <?php echo e(number_format($item->harga, 0, ',', '.')); ?></td>
                <td data-label="Status">
                    <?php if($item->status == 'aktif'): ?>
                        <span class="badge badge-success">Tersedia</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Tidak Tersedia</span>
                    <?php endif; ?>
                </td>
                <td data-label="Aksi">
                    <div class="action-buttons">
                        <a href="<?php echo e(route('produk.show', $item->id)); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if(in_array(session('user.role'), ['kepala_gudang', 'owner'])): ?>
                        <a href="<?php echo e(route('produk.edit', $item->id)); ?>" class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('produk.destroy', $item->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>Belum Ada Produk</h3>
        <p>Silakan tambahkan produk baru untuk memulai</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\produk\index.blade.php ENDPATH**/ ?>