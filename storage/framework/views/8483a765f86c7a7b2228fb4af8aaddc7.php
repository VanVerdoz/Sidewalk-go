<?php $__env->startSection('title', 'Dashboard Kepala Gudang'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .dashboard-title {
        font-size: 28px;
        color: var(--text);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .dashboard-subtitle {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 30px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        padding: 25px;
        border-radius: 20px;
        color: white;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.22);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        box-shadow: none;
    }

    .stat-card-icon i {
        font-size: 24px;
    }

.stat-card-title {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 8px;
}

.stat-card-value {
    font-size: 24px;
    font-weight: bold;
}

.request-emphasis .alert-item-title {
    font-size: 18px;
}

.request-emphasis .alert-item-stock {
    font-size: 16px;
}

.branch-section {
    background: var(--surface);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 18px;
    border: 1px solid var(--border);
}

    .branch-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
    }

    .branch-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .branch-title span.badge{
        display:inline-block;
        padding:6px 10px;
        border-radius:10px;
        font-size:12px;
        background: var(--accent-bg);
        color: var(--text);
        border:1px solid var(--border);
    }

    .req-table {
        width: 100%;
        border-collapse: collapse;
    }

.req-table th, .req-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
}

.req-table th {
    background: var(--table-head);
    color: var(--text);
    text-align: left;
}

    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .chart-container, .alert-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        
    }
    .chart-container .form-control {
        display:flex;
        align-items:center;
        gap:10px;
    }
    .chart-container select{
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding: 10px 12px;
        border-radius: 10px;
        border:1px solid var(--border);
        background: var(--surface);
        color: var(--text);
        min-width: 220px;
        box-shadow: var(--shadow-sm);
        transition: all .2s ease;
    }
    .chart-container select:focus{
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(255,107,53,0.12);
    }
    .chart-container label{
        font-size:13px;
        color: var(--muted);
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 20px;
    }
    .dark .chart-title { color: var(--text); }

    .chart-canvas {
        height: 420px;
    }

    .quick-actions {
        margin: 20px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .btn-action {
        padding: 10px 18px;
        border-radius: 10px;
        border: 1px solid var(--border);
        cursor: pointer;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        background: var(--surface);
        color: var(--text);
        box-shadow: var(--shadow-sm);
    }
    .btn-action-primary { background: linear-gradient(135deg,#ff6b35,#f7931e); color: #fff; }

    .alert-item {
        padding: 15px;
        background: var(--surface);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        margin-bottom: 10px;
        border: 1px solid var(--border);
    }

    .alert-item-title {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 5px;
    }

    .alert-item-stock {
        color: var(--primary);
        font-size: 14px;
    }

    .request-container {
        margin-top: 25px;
    }
    .req-table { width: 100%; border-collapse: collapse; }
    .req-table th, .req-table td { padding: 12px 16px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); }
    .req-table th { background: var(--table-head); text-align: left; }
.req-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:10px; font-size:12px; }
.status-acc { background: var(--surface); color:#10b981; border:1px solid #10b981; }
.status-pending { background: var(--surface); color:#f59e0b; border:1px solid #f59e0b; }
.btn-acc { background:#10b981; color:#fff; }
.btn-pending { background:#f59e0b; color:#fff; }

    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-card-value { font-size: 20px; }
        .stat-card-title { font-size: 12px; }
        .chart-container, .alert-container { padding: 20px; border-radius: 16px; }
        .branch-header { padding: 12px 14px; }
        .branch-title { font-size: 15px; }
        .req-table th, .req-table td { padding: 10px 12px; font-size: 13px; }
        .chart-canvas { height: 280px; }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .chart-canvas { height: 240px; }
        .branch-section { margin-bottom: 12px; }
        .alert-item { padding: 12px; border-radius: 10px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h1 class="dashboard-title">Dashboard Kepala Gudang</h1>
<p class="dashboard-subtitle">Monitoring stok dan inventori produk</p>

<!-- Statistics Cards -->
<div class="stats-grid">

    <div class="stat-card" style="background: linear-gradient(135deg, #f7931e 0%, #ff6b35 100%);">
        <div class="stat-card-icon">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-card-title">Total Penjualan Hari Ini</div>
        <div class="stat-card-value">Rp. <?php echo e(number_format($totalPenjualanHariIni ?? 0, 0, ',', '.')); ?></div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);">
        <div class="stat-card-icon">
            <i class="fas fa-cubes"></i>
        </div>
        <div class="stat-card-title">Stok Tersedia (Produk)</div>
        <div class="stat-card-value"><?php echo e($stokPercentProduk); ?>%</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="<?php echo e(route('produk.create')); ?>" class="btn-action btn-action-primary">
        <i class="fas fa-plus"></i>
        Tambah Produk
    </a>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <div class="chart-container" id="produk-terjual">
        <div class="branch-header">
            <div class="branch-title">
                Produk Terjual Hari Ini
                <?php if(!empty($selectedCabangId)): ?>
                    <?php
                        $namaCabang = optional($cabangList->firstWhere('id', $selectedCabangId))->nama_cabang;
                    ?>
                    - Cabang: <?php echo e($namaCabang ?? 'Semua'); ?>

                <?php else: ?>
                    - Semua Cabang
                <?php endif; ?>
            </div>
            <form action="<?php echo e(route('dashboard')); ?>" method="GET" style="display:flex; align-items:center; gap:10px;">
                <label for="cabang_id" style="font-size:13px; color: var(--muted);">Pilih Cabang</label>
                <select name="cabang_id" id="cabang_id" onchange="this.form.submit()" style="min-width: 200px;">
                    <option value="">Semua Cabang</option>
                    <?php $__currentLoopData = $cabangList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cb->id); ?>" <?php echo e((string)$selectedCabangId === (string)$cb->id ? 'selected' : ''); ?>>
                            <?php echo e($cb->nama_cabang); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </form>
        </div>
        <div class="chart-canvas">
            <canvas id="stokChart"></canvas>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stokChart').getContext('2d');
    const labels = <?php echo json_encode($chartLabels ?? [], 15, 512) ?>;
    const data = <?php echo json_encode($chartData ?? [], 15, 512) ?>;
    const isDark = document.documentElement.classList.contains('dark');
    const grid = isDark ? 'rgba(230,231,235,0.12)' : 'rgba(0,0,0,0.05)';
    const tick = getComputedStyle(document.documentElement).getPropertyValue('--primary') || '#ff6b35';

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Qty Terjual',
                data: data,
                backgroundColor: 'rgba(255, 107, 53, 0.8)',
                borderColor: 'rgba(255, 107, 53, 1)',
                borderWidth: 2,
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: grid, drawBorder: false },
                    ticks: { color: tick }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: tick }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\dashboard\kepala-gudang.blade.php ENDPATH**/ ?>