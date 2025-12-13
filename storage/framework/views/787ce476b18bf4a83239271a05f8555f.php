<?php $__env->startSection('title', 'Dashboard Admin'); ?>

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
        overflow: hidden;
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

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    .action-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        border-radius: 18px;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        border: none;
    }
    .action-card.primary { background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); }
    .action-card.secondary { background: linear-gradient(135deg, #374151 0%, #1f2937 100%); }
    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }
    .action-content { display: flex; flex-direction: column; }
    .action-title { font-size: 16px; font-weight: 700; }
    .action-desc { font-size: 12px; opacity: 0.9; }
    @media (max-width: 640px) { .chart-canvas { height: 320px; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h1 class="dashboard-title">Dashboard Admin</h1>
<p class="dashboard-subtitle">Monitoring laporan keuangan dan transaksi</p>

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
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-card-title">Total Laporan</div>
        <div class="stat-card-value"><?php echo e(number_format($totalLaporan ?? 0, 0, ',', '.')); ?></div>
    </div>
</div>

<!-- Sales Chart -->
<div class="chart-container">
    <h3 class="chart-title">Penjualan Per Bulan (6 Bulan Terakhir)</h3>
    <div class="chart-canvas">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<!-- Quick Create -->
<div class="action-grid">
    <a href="<?php echo e(route('laporan-keuangan.create')); ?>" class="action-card primary">
        <div class="action-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="action-content">
            <div class="action-title">Tambah Laporan Keuangan</div>
            <div class="action-desc">Buat laporan periode terbaru</div>
        </div>
    </a>
    <a href="<?php echo e(route('pengguna.create')); ?>" class="action-card secondary">
        <div class="action-icon"><i class="fas fa-user-plus"></i></div>
        <div class="action-content">
            <div class="action-title">Tambah Pengguna</div>
            <div class="action-desc">Tambahkan akun admin atau staf</div>
        </div>
    </a>
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
                label: 'Penjualan (Rp)',
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\RplBo\UasFix\BE-API-SW\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>