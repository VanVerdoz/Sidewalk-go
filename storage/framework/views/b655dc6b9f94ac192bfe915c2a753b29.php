<?php $__env->startSection('title', 'Riwayat Stok - ' . $cabang->nama_cabang); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4" style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
        <a href="<?php echo e(route('kepala.produk-raider.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="<?php echo e(route('kepala.produk-raider.create', $cabang->id)); ?>" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Kirim Stok Baru
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat Pengiriman Stok ke <?php echo e($cabang->nama_cabang); ?></h4>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th>Detail Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($req->tanggal)->format('d M Y H:i')); ?></td>
                            <td><?php echo e($req->catatan); ?></td>
                            <td>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <?php $__currentLoopData = $req->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <?php echo e($detail->produk->nama_produk ?? '-'); ?> 
                                        <span style="background:var(--primary); color:white; padding:2px 6px; border-radius:4px; font-size:11px;"><?php echo e($detail->jumlah); ?></span>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada riwayat pengiriman stok.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\kepala-gudang\produk-raider\show.blade.php ENDPATH**/ ?>