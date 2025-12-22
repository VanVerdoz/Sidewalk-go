<?php $__env->startSection('title', 'Laporan Keuangan'); ?>

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
        background: linear-gradient(135deg, #ff7a2a, #f7931e);
        color: #fff;
        border: none;
        border-radius: 999px;
        box-shadow: 0 10px 24px rgba(255, 107, 53, 0.25);
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }

    .btn-primary:hover {
        filter: brightness(1.04);
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(255, 107, 53, 0.28);
    }

    .btn-sm {
        padding: 10px 12px;
        font-size: 13px;
        border-radius: 999px;
        border: none;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }
    .btn-sm:hover { transform: translateY(-1.5px); box-shadow: 0 10px 24px rgba(0,0,0,0.10); }
    .btn-sm i { font-size: 15px; }

    .btn-edit {
        background: #10b981;
        color: #fff;
    }

    .btn-delete {
        background: #ef4444;
        color: #fff;
    }
    .btn-view {
        background: #1e80ff;
        color: #fff;
    }
    .action-buttons .btn { border: none; }

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

    .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    /* Custom Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        animation: modalSlideIn 0.3s ease;
        border: 1px solid var(--border);
        color: var(--text);
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-icon { font-size: 64px; color: var(--danger); margin-bottom: 20px; }

    .modal-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 10px;
    }

    .modal-message {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 30px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    /* uses global .btn-confirm and .btn-cancel from layout */

    .btn-view {
        background: #2196F3;
        color: white;
    }

    .btn-view:hover {
        background: #0b7dda;
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
    <h2 class="page-title">Laporan Keuangan</h2>
    <?php if(in_array(session('user.role'), ['admin'])): ?>
    <a href="<?php echo e(route('laporan-keuangan.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Laporan
    </a>
    <?php endif; ?>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<div class="table-container">
    <?php if($laporan->count() > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>Cabang</th>
                <th>Total Pendapatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td data-label="No"><?php echo e($index + 1); ?></td>
                <td data-label="Periode"><?php echo e(\Carbon\Carbon::parse($item->periode_awal)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($item->periode_akhir)->format('d/m/Y')); ?></td>
                <td data-label="Cabang"><?php echo e($item->cabang->nama_cabang ?? '-'); ?></td>
                <td data-label="Total Pendapatan">Rp. <?php echo e(number_format($item->total_pendapatan, 0, ',', '.')); ?></td>
                <td data-label="Aksi">
                    <div class="action-buttons">
                        <a href="<?php echo e(route('laporan-keuangan.show', $item->id)); ?>" class="btn btn-sm btn-view" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if(in_array(session('user.role'), ['admin'])): ?>
                        <a href="<?php echo e(route('laporan-keuangan.edit', $item->id)); ?>" class="btn btn-sm btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form id="delete-form-<?php echo e($item->id); ?>" action="<?php echo e(route('laporan-keuangan.destroy', $item->id)); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="button" class="btn btn-sm btn-delete" onclick="confirmDelete(<?php echo e($item->id); ?>, '<?php echo e(\Carbon\Carbon::parse($item->periode_awal)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($item->periode_akhir)->format('d/m/Y')); ?>')" title="Hapus">
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
        <i class="fas fa-chart-line"></i>
        <h3>Belum Ada Laporan</h3>
        <p>Silakan tambahkan laporan keuangan untuk memulai</p>
    </div>
    <?php endif; ?>
</div>

<!-- Custom Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p class="modal-message" id="deleteMessage">Apakah Anda yakin ingin menghapus laporan ini?</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-danger" onclick="submitDelete()">
                <i class="fas fa-check"></i>
                Ya, Hapus
            </button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">
                <i class="fas fa-times"></i>
                Batal
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let deleteFormId = null;

    function confirmDelete(id, periode) {
        deleteFormId = id;
        document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus laporan periode "${periode}"?`;
        document.getElementById('deleteModal').classList.add('active');
    }

    function submitDelete() {
        if (deleteFormId) {
            document.getElementById('delete-form-' + deleteFormId).submit();
        }
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.remove('active');
        deleteFormId = null;
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\laporan-keuangan\index.blade.php ENDPATH**/ ?>