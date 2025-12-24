<?php $__env->startSection('title', 'Kirim Stok ke Raider'); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="d-flex align-items-center mb-4" style="display:flex; align-items:center; gap:15px; margin-bottom:1.5rem;">
        <a href="<?php echo e(route('kepala.produk-raider.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h2 style="margin:0; font-size:1.5rem; font-weight:600;">Kirim Stok ke <?php echo e($cabang->nama_cabang); ?></h2>
    </div>

    <?php if(session('error')): ?>
    <div class="alert alert-error mb-3">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('kepala.produk-raider.store', $cabang->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="alert alert-info mb-4" style="background: #e3f2fd; border-left: 4px solid #2196F3; color: #0d47a1;">
                    <i class="fas fa-info-circle"></i> Masukkan jumlah barang yang diberikan ke Cabang. (Stok tidak akan dipotong dari Gudang)
                </div>

                <div class="form-group mb-4" style="margin-bottom:1rem;">
                    <label class="block mb-2 font-bold" style="display:block; margin-bottom:0.5rem; font-weight:600;">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Stok tambahan untuk event..."></textarea>
                </div>

                <div class="mb-4" style="margin-bottom:1.5rem;">
                    <h4 class="font-bold mb-3" style="font-weight:600; margin-bottom:1rem;">Pilih Produk & Jumlah</h4>
                    
                    <?php if(count($stokCabang) > 0): ?>
                        <div id="produk-container">
                            <div class="produk-row form-row mb-3 align-items-end" style="display:flex; gap:10px; align-items:flex-end; margin-bottom:10px;">
                                <div class="col-6" style="flex:2;">
                                    <label style="display:block; margin-bottom:4px; font-size:13px;">Produk</label>
                                    <select name="produk_id[]" required style="width:100%; padding:10px; border-radius:12px; border:1px solid var(--border); background:var(--surface); color:var(--text);">
                                        <option value="">-- Pilih Produk --</option>
                                        <?php $__currentLoopData = $stokCabang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>">
                                                <?php echo e($item->nama_produk); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-4" style="flex:1;">
                                    <label style="display:block; margin-bottom:4px; font-size:13px;">Jumlah Kirim</label>
                                    <input type="number" name="jumlah[]" min="1" required placeholder="Jml" style="width:100%; padding:10px; border-radius:12px; border:1px solid var(--border); background:var(--surface); color:var(--text);">
                                </div>
                                <div class="col-2" style="width:auto;">
                                    <button type="button" class="btn btn-danger btn-remove-row" style="display:none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-product-btn" class="btn btn-secondary btn-small mt-2">
                            <i class="fas fa-plus"></i> Tambah Baris Produk
                        </button>

                        <div class="d-flex justify-content-end mt-4" style="display:flex; justify-content:flex-end; margin-top:1.5rem;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Stok Sekarang
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-error">
                            Belum ada produk terdaftar.
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(count($stokCabang) > 0): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('produk-container');
    const addBtn = document.getElementById('add-product-btn');

    // Function to update remove buttons visibility
    function updateRemoveButtons() {
        const rows = container.querySelectorAll('.produk-row');
        rows.forEach(row => {
            const btn = row.querySelector('.btn-remove-row');
            if (rows.length > 1) {
                btn.style.display = 'inline-flex';
            } else {
                btn.style.display = 'none';
            }
        });
    }

    // Add new row
    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.produk-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('select').value = '';
        newRow.querySelector('input').value = '';
        newRow.querySelector('input').removeAttribute('max');
        newRow.querySelector('input').placeholder = 'Jml';
        
        container.appendChild(newRow);
        updateRemoveButtons();
    });

    // Remove row
    container.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-row')) {
            e.target.closest('.produk-row').remove();
            updateRemoveButtons();
        }
    });

    // Validate stock quantity on change
    container.addEventListener('change', function(e) {
        if (e.target.tagName === 'SELECT') {
            const option = e.target.options[e.target.selectedIndex];
            const max = option.getAttribute('data-max');
            const row = e.target.closest('.produk-row');
            const input = row.querySelector('input[type="number"]');
            
            if (max) {
                // Parse float to remove trailing zeros for display
                const maxDisplay = parseFloat(max);
                input.max = max; // Keep original for validation
                input.placeholder = 'Max: ' + maxDisplay;
            } else {
                input.removeAttribute('max');
                input.placeholder = 'Jml';
            }
        }
    });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\kepala-gudang\produk-raider\create.blade.php ENDPATH**/ ?>