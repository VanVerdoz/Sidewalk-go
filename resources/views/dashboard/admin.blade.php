@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
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
        background: #ff7a2a;
        padding: 25px 72px 25px 25px;
        border-radius: 22px;
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.30);
        border: none;
        position: relative;
        overflow: hidden;
    }
    .stat-card-icon {
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.28);
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0;
        color: #ffffff;
        box-shadow: none;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
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

    @media (max-width: 768px) {
        .chart-container {
            padding: 15px;
        }
        .chart-title {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .stats-grid {
            gap: 12px;
            grid-template-columns: 1fr; /* Force 1 column on mobile for full width cards */
        }
        .stat-card {
            padding: 20px;
        }
        .stat-card-icon {
            width: 36px;
            height: 36px;
            font-size: 18px;
        }
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
@endpush

@section('content')

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-title">Total Penjualan</div>
        <div class="stat-card-value">Rp. {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-card-title">Transaksi Harian</div>
        <div class="stat-card-value">{{ number_format($transaksiHarian ?? 0, 0, ',', '.') }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-card-title">Total Laporan</div>
        <div class="stat-card-value">{{ number_format($totalLaporan ?? 0, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Sales Chart -->
<div class="chart-container">
    <h3 class="chart-title">Penjualan Per Bulan (6 Bulan Terakhir)</h3>
    <div class="chart-canvas">
        <canvas id="salesChart"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const labels = @json($chartLabels ?? []);
    const data = @json($chartData ?? []);
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
@endpush
