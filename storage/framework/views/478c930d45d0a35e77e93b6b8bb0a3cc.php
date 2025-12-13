<?php $__env->startSection('title', 'Tambah Transaksi Penjualan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-container {
        background: var(--surface);
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: var(--shadow-sm);
        width: 100%;
        max-width: none;
        border: 1px solid var(--border);
        color: var(--text);
    }
    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--text);
    }
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px;
        color: var(--text);
    }
    .form-control, .form-select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        font-size: 14px;
        background: var(--surface);
        color: var(--text);
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #ff6b35;
        box-shadow: 0 0 0 2px rgba(255,107,53,0.15);
    }
    .form-text {
        font-size: 12px;
        color: var(--muted);
    }
    .form-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }
    .form-actions .btn {
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .form-actions .btn-primary {
        background: linear-gradient(135deg,#ff6b35,#f7931e);
        color: #fff;
    }
    .form-actions .btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    @media (max-width: 768px) {
        .form-container { padding: 18px; border-radius: 14px; }
        .page-title { font-size: 22px; }
    }
    @media (max-width: 640px) {
        .form-container { padding: 14px; border-radius: 12px; }
        .form-actions { flex-direction: column; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="page-title">Tambah Transaksi Penjualan</h2>

<div class="form-container">
    <form action="<?php echo e(route('penjualan.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label class="form-label">Cabang</label>
            <select name="cabang_id" class="form-select" required>
                <option value="">-- Pilih Cabang --</option>
                <?php $__currentLoopData = $cabang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cb->id); ?>" <?php echo e(old('cabang_id') == $cb->id ? 'selected' : ''); ?>>
                        <?php echo e($cb->nama_cabang ?? 'Cabang '.$cb->id); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['cabang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal" class="form-control" value="<?php echo e(old('tanggal', now()->format('Y-m-d'))); ?>" required>
            <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <option value="tunai" <?php echo e(old('metode_pembayaran')=='tunai' ? 'selected' : ''); ?>>Tunai</option>
                <option value="transfer" <?php echo e(old('metode_pembayaran')=='transfer' ? 'selected' : ''); ?>>Transfer</option>
                <option value="qris" <?php echo e(old('metode_pembayaran')=='qris' ? 'selected' : ''); ?>>QRIS</option>
            </select>
            <?php $__errorArgs = ['metode_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Nama Produk</label>
            <select id="produkSelect" class="form-select">
                <option value="">-- Pilih Produk --</option>
                <?php $__currentLoopData = $produk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" data-kategori="<?php echo e($p->kategori); ?>" data-harga="<?php echo e($p->harga); ?>" data-deskripsi="<?php echo e($p->deskripsi); ?>"><?php echo e($p->nama_produk); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="form-text">Memilih produk akan otomatis mengisi kategori dan harga.</div>
        </div>

        <input type="hidden" name="produk_id" id="produkIdHidden" value="">

        <div class="form-group">
            <label class="form-label">Kategori (otomatis)</label>
            <input id="kategoriAuto" type="text" class="form-control" placeholder="-" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Harga (otomatis)</label>
            <input id="hargaAuto" type="text" class="form-control" placeholder="-" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Total Pembayaran (Rp)</label>
            <input id="totalInput" type="number" name="total" class="form-control" value="<?php echo e(old('total')); ?>" min="0" step="100" required>
            <div class="form-text">Otomatis dari harga produk, bisa diedit manual jika perlu.</div>
            <?php $__errorArgs = ['total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea id="keteranganField" name="keterangan" class="form-control" rows="3"><?php echo e(old('keterangan')); ?></textarea>
            <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="form-text" style="color:red;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Simpan Transaksi
            </button>
            <a href="<?php echo e(route('penjualan.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
    (function() {
        const sel = document.getElementById('produkSelect');
        const kategoriEl = document.getElementById('kategoriAuto');
        const hargaEl = document.getElementById('hargaAuto');

        function formatRupiah(n) {
            if (n == null || n === '') return '-';
            try {
                const num = Number(n);
                return 'Rp. ' + num.toLocaleString('id-ID');
            } catch(e) { return String(n); }
        }

        sel && sel.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const kategori = opt ? opt.dataset.kategori : '';
            const harga = opt ? opt.dataset.harga : '';
            const deskripsi = opt ? opt.dataset.deskripsi : '';
            kategoriEl.value = kategori ? kategori : '-';
            hargaEl.value = harga ? formatRupiah(harga) : '-';
            const totalEl = document.getElementById('totalInput');
            if (totalEl) {
                totalEl.value = harga ? Number(harga) : '';
            }
            const hid = document.getElementById('produkIdHidden');
            if (hid) hid.value = opt ? opt.value : '';
            const ket = document.getElementById('keteranganField');
            if (ket && (!ket.value || ket.value.trim() === '')) {
                ket.value = deskripsi || '';
            }
        });
    })();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views/penjualan/create.blade.php ENDPATH**/ ?>