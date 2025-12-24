<?php $__env->startSection('title', 'Produk per Cabang'); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Produk per Cabang</h4>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Cabang</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $cabangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($cabang->nama_cabang); ?></td>
                            <td><?php echo e($cabang->alamat); ?></td>
                            <td class="table-actions">
                                <a href="<?php echo e(route('kepala.produk-raider.show', $cabang->id)); ?>" class="btn btn-small btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="<?php echo e(route('kepala.produk-raider.create', $cabang->id)); ?>" class="btn btn-small btn-success">
                                    <i class="fas fa-paper-plane"></i> Kirim Stok
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data cabang.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\kepala-gudang\produk-raider\index.blade.php ENDPATH**/ ?>