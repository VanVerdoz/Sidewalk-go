<?php $__env->startSection('title', 'Permintaan Stok – Cabang ' . ($cabang->nama_cabang ?? '')); ?>

<?php $__env->startPush('styles'); ?>
<style>
.page-title { font-size: 24px; font-weight: 600; margin-bottom: 12px; color: var(--text); }
.sub-title { color: var(--muted); margin-bottom: 20px; }
.wib-time { font-size: 12px; color: var(--muted); margin-bottom: 10px; }
.table-card { background: var(--surface); border-radius: 16px; box-shadow: var(--shadow-sm); border:1px solid var(--border); padding: 16px; }
.req-table { width: 100%; border-collapse: collapse; }
.req-table th, .req-table td { padding: 12px 14px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); }
.req-table th { background: var(--table-head); }
.actions { display:flex; gap:8px; flex-wrap: wrap; }
.btn { padding:8px 12px; border:none; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:6px; text-decoration:none; }
.btn-xs { padding:6px 10px; font-size:12px; border-radius:8px; }
.btn-primary { background:#F36F45; color:#fff; }
.btn-secondary { background:#6b7280; color:#fff; }
.status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:10px; font-size:12px; }
.status-acc { background: var(--surface); color:#10b981; border:1px solid #10b981; }
.status-pending { background: var(--surface); color:#f59e0b; border:1px solid #f59e0b; }
.btn-acc { background:#10b981; color:#fff; }
.btn-pending { background:#f59e0b; color:#fff; }
@media (max-width: 640px) {
    .actions { flex-direction: column; }
    .actions .btn, .actions .status-badge { width: 100%; justify-content: center; }
}
@media (max-width: 640px) {
    .req-table thead { display:none; }
    .req-table, .req-table tbody { display:block; width:100%; }
    .req-table tr { display:block; margin-bottom:12px; background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:8px; }
    .req-table td { display:inline-block; width:calc(50% - 8px); margin:0 4px 8px; white-space:normal; vertical-align:top; }
    .req-table td::before { content: attr(data-label); display:block; font-weight:600; color:var(--muted); margin-bottom:4px; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Permintaan Stok – Cabang <?php echo e($cabang->nama_cabang); ?></h2>
<div class="sub-title">Daftar permintaan stok pada cabang ini</div>
<div class="wib-time">Waktu WIB: <span id="waktu-wib"></span></div>

<div class="table-card">
    <div style="margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
        <a href="<?php echo e(route('kepala.permintaan-stok.index')); ?>" class="btn btn-secondary btn-xs"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <table class="req-table">
        <thead>
            <tr>
                <th>Raider</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Aksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $produkNama = optional(optional($req->details->first())->produk)->nama_produk ?? '-';
                    $jumlah = $req->details->sum('jumlah');
                    $status = $req->status_permintaan ?? 'pending';
                    $raiderObj = optional($req->raider);
                    $raiderNama = ($raiderObj->nama_lengkap ?? $raiderObj->username ?? '-') . (isset($raiderObj->username) ? ' (' . $raiderObj->username . ')' : '');
                ?>
                <tr>
                    <td data-label="Raider"><?php echo e($raiderNama); ?></td>
                    <td data-label="Produk"><?php echo e($produkNama); ?></td>
                    <td data-label="Jumlah"><?php echo e(number_format($jumlah,0,',','.')); ?></td>
                    <td data-label="Tanggal"><?php echo e(\Carbon\Carbon::parse($req->dibuat_pada)->timezone('Asia/Jakarta')->format('d/m/Y H:i')); ?> WIB</td>
                    <td data-label="Aksi">
                        <div class="actions">
                            <form action="<?php echo e(route('kepala.permintaan-stok.approve', $req->id_permintaan)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary btn-xs"><i class="fas fa-check"></i> ACC</button>
                            </form>
                            <form action="<?php echo e(route('kepala.permintaan-stok.pending', $req->id_permintaan)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-secondary btn-xs"><i class="fas fa-clock"></i> Pending</button>
                            </form>
                        </div>
                    </td>
                    <td data-label="Status">
                        <?php $st = $req->status_permintaan ?? 'pending'; ?>
                        <?php if($st === 'disetujui'): ?>
                            <span class="status-badge status-acc"><i class="fas fa-check"></i> ACC</span>
                        <?php else: ?>
                            <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6">Belum ada permintaan stok pada cabang ini.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="margin-top:10px;"><?php echo e($permintaan->links()); ?></div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
(function(){
    function updateWIB(){
        var now = new Date();
        var opts = { timeZone: 'Asia/Jakarta', hour12: false, year:'numeric', month:'2-digit', day:'2-digit', hour:'2-digit', minute:'2-digit', second:'2-digit' };
        var fmt = new Intl.DateTimeFormat('id-ID', opts).format(now);
        var el = document.getElementById('waktu-wib');
        if(el) el.textContent = fmt + ' WIB';
    }
    updateWIB();
    setInterval(updateWIB, 1000);
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views/kepala-gudang/permintaan/cabang.blade.php ENDPATH**/ ?>