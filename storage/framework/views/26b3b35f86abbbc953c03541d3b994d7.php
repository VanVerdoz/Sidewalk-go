<?php $__env->startSection('title', 'Dashboard Owner'); ?>

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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        padding: 25px;
        border-radius: 20px;
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        border: none;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        color: #ffffff;
    }

    .stat-card-icon i {
        font-size: 24px;
    }

    .stat-card-title {
        font-size: 13px;
        opacity: 0.9;
        margin-bottom: 8px;
        color: #ffffff;
    }

    .stat-card-value {
        font-size: 24px;
        font-weight: bold;
        color: #ffffff;
    }

    .chart-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        color: var(--text);
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 20px;
    }
    .dark .chart-title { color: var(--text); }

    .chart-canvas {
        height: 350px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h1 class="dashboard-title">Dashboard Owner</h1>
<p class="dashboard-subtitle">Monitoring seluruh aktivitas sistem Sidewalk.Go</p>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-title">Total Penjualan</div>
        <div class="stat-card-value">Rp. <?php echo e(number_format($totalPenjualan ?? 0, 0, ',', '.')); ?></div>
    </div>

    

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-card-title">Transaksi Harian</div>
        <div class="stat-card-value"><?php echo e(number_format($transaksiHarian ?? 0, 0, ',', '.')); ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-store"></i>
        </div>
        <div class="stat-card-title">Total Cabang</div>
        <div class="stat-card-value"><?php echo e(number_format($totalCabang ?? 0, 0, ',', '.')); ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-card-title">Total Pengguna</div>
        <div class="stat-card-value"><?php echo e(number_format($totalPengguna ?? 0, 0, ',', '.')); ?></div>
    </div>
</div>

<!-- Sales Chart -->
<div class="chart-container">
    <h3 class="chart-title">Top Produk Terlaris</h3>
    <div class="chart-canvas">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<div class="chart-container" style="margin-top:20px;">
    <h3 class="chart-title">Penjualan 7 Hari Terakhir</h3>
    <div class="chart-canvas">
        <canvas id="salesTrendChart"></canvas>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
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
                label: 'Jumlah Terjual',
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

    const ctx2 = document.getElementById('salesTrendChart').getContext('2d');
    const lineLabels = <?php echo json_encode($lineLabels ?? [], 15, 512) ?>;
    const lineData = <?php echo json_encode($lineData ?? [], 15, 512) ?>;

    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: lineData,
                backgroundColor: 'rgba(255, 107, 53, 0.2)',
                borderColor: 'rgba(255, 107, 53, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(255, 107, 53, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: grid, drawBorder: false },
                    ticks: {
                        color: tick,
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: tick }
                }
            },
            plugins: {
                legend: { labels: { color: tick } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views/dashboard/owner.blade.php ENDPATH**/ ?>