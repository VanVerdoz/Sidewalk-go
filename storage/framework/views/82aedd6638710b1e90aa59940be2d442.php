<?php $__env->startSection('title', 'Dashboard Overview'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .dashboard-title {
        font-size: 28px;
        color: var(--text);
        margin-bottom: 30px;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--surface);
        padding: 25px;
        border-radius: 20px;
        color: var(--text);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 150px;
        height: 150px;
        background: var(--accent-bg);
        border-radius: 50%;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        background: var(--accent-bg);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        color: var(--text);
    }

    .stat-card-icon i {
        font-size: 24px;
    }

    .stat-card-title {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 8px;
    }

    .stat-card-value {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .stat-card-change {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stat-card-change i {
        font-size: 10px;
    }

    .chart-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
        margin-bottom: 30px;
        border: 1px solid var(--border);
        color: var(--text);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .chart-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text);
    }

    .chart-filters {
        display: flex;
        gap: 10px;
    }

    .chart-filter-btn {
        padding: 8px 20px;
        border: 2px solid #ff6b35;
        background: var(--surface);
        color: var(--text);
        border-radius: 20px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }

    .chart-filter-btn.active,
    .chart-filter-btn:hover {
        background: #ff6b35;
        color: white;
    }

    .chart-canvas {
        position: relative;
        height: 350px;
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

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="dashboard-title">Dashboard Overview</h2>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-title">Total Penjualan</div>
        <div class="stat-card-value">Rp. <?php echo e(number_format($totalPenjualan ?? 0, 0, ',', '.')); ?></div>
        <div class="stat-card-change">
            <i class="fas fa-arrow-up"></i>
            <span>12.5%</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="stat-card-title">Stok Tersedia</div>
        <div class="stat-card-value"><?php echo e(number_format($stokTersedia ?? 0, 0, ',', '.')); ?> %</div>
        <div class="stat-card-change">
            <i class="fas fa-arrow-down"></i>
            <span>1.7%</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-card-title">Transaksi Harian</div>
        <div class="stat-card-value"><?php echo e(number_format($transaksiHarian ?? 0, 0, ',', '.')); ?></div>
        <div class="stat-card-change">
            <i class="fas fa-arrow-up"></i>
            <span>17.3%</span>
        </div>
    </div>
</div>

<!-- Sales Chart -->
<div class="chart-container">
    <div class="chart-header">
        <h3 class="chart-title">Grafik Penjualan</h3>
        <div class="chart-filters">
            <button class="chart-filter-btn active">Mingguan</button>
            <button class="chart-filter-btn">Bulanan</button>
        </div>
    </div>
    <div class="chart-canvas">
        <canvas id="salesChart"></canvas>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');

    const labels = <?php echo json_encode($chartLabels ?? [], 15, 512) ?>;
    const data = <?php echo json_encode($chartData ?? [], 15, 512) ?>;
    const isDark = document.documentElement.classList.contains('dark');
    const grid = isDark ? 'rgba(230,231,235,0.12)' : 'rgba(0,0,0,0.05)';
    const tick = isDark ? '#e6e7eb' : '#374151';

    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Target',
                data: data.map(v => Math.round(v * 1.2)),
                backgroundColor: 'rgba(255, 180, 160, 0.6)',
                borderColor: 'rgba(255, 180, 160, 1)',
                borderWidth: 2,
                borderRadius: 10,
            }, {
                label: 'Penjualan',
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
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderRadius: 8,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: grid, drawBorder: false },
                    ticks: { color: tick, font: { size: 12 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: tick, font: { size: 12 } }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views\dashboard\index.blade.php ENDPATH**/ ?>