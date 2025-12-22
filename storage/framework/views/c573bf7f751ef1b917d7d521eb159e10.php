<?php $__env->startSection('title', 'Detail Permintaan Produk'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.page-title { font-size: 24px; font-weight: 600; margin-bottom: 12px; color: var(--text); }
.detail-card { background:#fff; border:1px solid #eef0f3; border-radius:18px; padding:18px; box-shadow:0 10px 18px rgba(0,0,0,0.06); color:#1f2937; }
.dark .detail-card { background: var(--surface); border-color: var(--border); color: var(--text); box-shadow: var(--shadow-sm); }
.grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-top:8px; }
.item { background: #fafafa; border:1px solid #eef0f3; border-radius:12px; padding:12px; }
.dark .item { background: var(--surface); border-color: var(--border); }
.label { font-size:12px; color:#6b7280; margin-bottom:4px; }
.dark .label { color: var(--muted); }
.value { font-weight:600; }
.actions { margin-top:16px; display:flex; gap:10px; }
.btn { padding:10px 14px; border:none; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
.btn-approve { background:#10b981; color:#fff; }
.btn-reject { background:#ef4444; color:#fff; }
.products { margin-top:16px; }
.prod-row { display:flex; justify-content:space-between; align-items:center; padding:10px 12px; border-bottom:1px dashed #e5e7eb; }
.dark .prod-row { border-color: var(--border); }
.prod-info { flex: 1; }
.prod-name { font-weight:600; display:block; }
.prod-price { font-size:12px; color:#6b7280; margin-top:2px; }
.prod-qty-edit { display:flex; align-items:center; gap:8px; }
.qty-input { width:80px; padding:6px 10px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; text-align:center; }
.dark .qty-input { background: var(--surface); border-color: var(--border); color: var(--text); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Detail Permintaan Produk</h2>

<div class="detail-card">
    <div class="grid">
        <div class="item">
            <div class="label">Rider</div>
            <div class="value"><?php echo e(optional($req->raider)->nama_lengkap ?? optional($req->raider)->username ?? '-'); ?></div>
        </div>
        <div class="item">
            <div class="label">Cabang</div>
            <div class="value"><?php echo e(optional($req->cabang)->nama_cabang ?? '-'); ?></div>
        </div>
        <div class="item">
            <div class="label">Tanggal permintaan</div>
            <div class="value"><?php echo e(\Carbon\Carbon::parse($req->tanggal)->format('d/m/Y H:i')); ?></div>
        </div>
        <div class="item">
            <div class="label">Status</div>
            <div class="value"><?php echo e(ucfirst($req->status ?? 'pending')); ?></div>
        </div>
    </div>

    <div class="grid" style="margin-top:12px;">
        <div class="item" style="grid-column: 1 / -1;">
            <div class="label">Catatan Rider</div>
            <div class="value"><?php echo e($req->catatan ?? '-'); ?></div>
        </div>
    </div>

    <form action="<?php echo e(route('kepala.permintaan-stok.approve', $req->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="products">
            <?php $__empty_1 = true; $__currentLoopData = $req->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="prod-row">
                    <div class="prod-info">
                        <div class="prod-name"><?php echo e(optional($d->produk)->nama_produk ?? '-'); ?></div>
                        <div class="prod-price">Harga: Rp <?php echo e(number_format(optional($d->produk)->harga ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div class="prod-qty-edit">
                        <label style="font-size:13px; color:var(--muted);">Jumlah:</label>
                        <input type="number" name="details[<?php echo e($d->id); ?>][jumlah]" value="<?php echo e($d->jumlah); ?>" min="1" class="qty-input">
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="form-text">Tidak ada produk pada permintaan ini.</div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-approve"><i class="fas fa-check"></i> Setujui & Simpan</button>
    </form>
            <form action="<?php echo e(route('kepala.permintaan-stok.reject', $req->id)); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-reject"><i class="fas fa-times"></i> Tolak</button>
            </form>
        </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\kepala-gudang\permintaan\detail.blade.php ENDPATH**/ ?>