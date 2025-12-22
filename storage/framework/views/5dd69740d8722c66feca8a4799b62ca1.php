<?php $__env->startSection('title', 'Manajemen Pengguna'); ?>

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

    .btn-edit:hover {
        background: #45a049;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .btn-delete:hover {
        background: #da190b;
    }

    .table-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
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

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        display: inline-block;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-role {
        background: #fff3cd;
        color: #856404;
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

    .modal-icon {
        font-size: 64px;
        color: #f44336;
        margin-bottom: 20px;
    }

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

        /* Fix table layout for mobile to accommodate badges */
        table td:nth-child(5) {
            width: auto !important;
            min-width: 100px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2 class="page-title">Manajemen Pengguna</h2>
    <?php if(session('user.role') === 'admin'): ?>
    <a href="<?php echo e(route('pengguna.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Pengguna
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
    <?php if($pengguna->count() > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <?php if(session('user.role') === 'admin'): ?>
                <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $pengguna; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td data-label="No"><?php echo e($index + 1); ?></td>
                <td data-label="Nama Lengkap"><?php echo e($user->nama_lengkap); ?></td>
                <td data-label="Username"><?php echo e($user->username); ?></td>
                <td data-label="Role">
                    <span class="badge badge-role">
                        <?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>

                    </span>
                </td>
                <td data-label="Status">
                    <?php if($user->status === 'active'): ?>
                        <span class="badge badge-active">Aktif</span>
                    <?php else: ?>
                        <span class="badge badge-inactive">Tidak Aktif</span>
                    <?php endif; ?>
                </td>
                <?php if(session('user.role') === 'admin'): ?>
                <td data-label="Aksi">
                    <div class="action-buttons">
                        <a href="<?php echo e(route('pengguna.edit', $user->id)); ?>" class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form id="delete-form-<?php echo e($user->id); ?>" action="<?php echo e(route('pengguna.destroy', $user->id)); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="button" class="btn btn-sm btn-delete" onclick="confirmDelete(<?php echo e($user->id); ?>, '<?php echo e($user->nama_lengkap); ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>Belum Ada Pengguna</h3>
        <p>Silakan tambahkan pengguna baru untuk memulai</p>
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
        <p class="modal-message" id="deleteMessage">Apakah Anda yakin ingin menghapus pengguna ini?</p>
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

    function confirmDelete(id, name) {
        deleteFormId = id;
        document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus pengguna "${name}"?`;
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\pengguna\index.blade.php ENDPATH**/ ?>