@extends('layouts.app')

@section('title', 'Dashboard Raider')

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
        text-shadow: 0 1px 1px rgba(0,0,0,0.15);
    }

    .stat-card-value {
        font-size: 24px;
        font-weight: bold;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.18);
    }

    .chart-container {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        color: var(--text);
    }
    .hero-banner {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 14px;
        padding: 18px;
        border-radius: 20px;
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: #fff;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        margin-bottom: 16px;
        border: none;
    }
    .hero-left {
        display: grid;
        grid-template-columns: 1fr;
        gap: 8px;
        align-content: center;
    }
    .hero-title {
        font-size: 18px;
        font-weight: 700;
    }
    .hero-subtitle {
        font-size: 13px;
        opacity: 0.9;
    }
    .hero-meta-row {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,0.18);
        color: #fff;
        font-size: 12px;
    }
    .hero-right {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
        align-content: center;
    }
    .hero-card {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 14px;
        color: #fff;
    }
    .hero-card .label { font-size: 13px; opacity: 0.95; }
    .hero-card .value { font-size: 18px; font-weight: 700; }
    .hero-card .icon { color: #fff; }
    .hero-card .icon i { font-size: 16px; }
    .hero-card.pending {
        background: linear-gradient(135deg, #f9c74f 0%, #f9844a 100%);
        box-shadow: 0 4px 12px rgba(249, 132, 74, 0.35);
    }
    .hero-card.approved {
        background: linear-gradient(135deg, #ff6b9f 0%, #c241ff 100%);
        box-shadow: 0 4px 12px rgba(255, 107, 159, 0.35);
    }
    .hero-header-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #fff;
        color: var(--text);
        box-shadow: var(--shadow-sm);
        margin-bottom: 8px;
    }
    .hero-visual {
        justify-self: end;
        width: 100%;
        max-width: 280px;
        min-height: 120px;
        border-radius: 16px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(2px);
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 20px;
    }
    .dark .chart-title { color: var(--text); }

    .chart-canvas {
        height: 65vh;
        min-height: 380px;
    }

    .quick-actions {
        margin: 25px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-action {
        padding: 12px 18px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        background: var(--surface);
        color: var(--text);
        box-shadow: 0 8px 20px rgba(0,0,0,0.10);
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .btn-action:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.12); }
    .btn-action:active { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(0,0,0,0.11); }
    .btn-action:focus-visible { outline: none; box-shadow: 0 0 0 4px rgba(255,107,53,0.16); }
    .btn-action i { font-size: 16px; }

    .btn-action-primary {
        background: linear-gradient(135deg,#ff7a2a,#f7931e);
        color: #fff;
        border: none;
        box-shadow: 0 12px 28px rgba(255,107,53,0.28);
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }
    .btn-action-primary:hover { filter: brightness(1.04); transform: translateY(-2px); }
    @media (max-width: 1024px) {
        .dashboard-title { font-size: 24px; }
        .stats-grid { gap: 16px; }
        .chart-canvas { height: 50vh; min-height: 320px; }
    }
    @media (max-width: 768px) {
        .dashboard-title { font-size: 20px; margin-bottom: 8px; }
        .dashboard-subtitle { font-size: 13px; margin-bottom: 16px; }
        .hero-banner { grid-template-columns: 1fr; padding: 14px; border-radius: 16px; }
        .hero-title { font-size: 16px; }
        .hero-card .value { font-size: 16px; }
        .stats-grid { grid-template-columns: 1fr; gap: 12px; }
        .stat-card { padding: 16px; border-radius: 16px; }
        .stat-card-icon { width: 42px; height: 42px; margin-bottom: 10px; }
        .stat-card-value { font-size: 20px; }
        .quick-actions { margin: 16px 0; gap: 8px; }
        .btn-action { width: 100%; justify-content: center; padding: 10px 12px; }
        .chart-container { padding: 16px; border-radius: 16px; }
        .chart-title { font-size: 16px; margin-bottom: 12px; }
        .chart-canvas { height: 42vh; min-height: 260px; }
    }
    @media (max-width: 480px) {
        .dashboard-title { font-size: 18px; }
        .dashboard-subtitle { font-size: 12px; }
        .btn-action { padding: 8px 10px; font-size: 13px; }
        .chart-canvas { height: 38vh; min-height: 220px; }
    }
</style>
@endpush

@section('content')
<!-- Hero mengikuti layout header; banner lokal dihapus -->

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-card-title">Transaksi Hari Ini</div>
        <div class="stat-card-value">{{ number_format($transaksiHarian ?? 0, 0, ',', '.') }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-title">Penjualan Hari Ini</div>
        <div class="stat-card-value">Rp. {{ number_format($totalPenjualanHariIni ?? 0, 0, ',', '.') }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-card-title">Transaksi Minggu Ini</div>
        <div class="stat-card-value">{{ number_format($transaksiMingguIni ?? 0, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('produk.create') }}" class="btn-action btn-action-primary">
        <i class="fas fa-plus"></i>
        Tambah Produk
    </a>
    <a href="{{ route('raider.permintaan-stok.create') }}" class="btn-action btn-action-primary">
        <i class="fas fa-box-open"></i>
        Request Stok Produk
    </a>
    <a href="{{ route('penjualan.index') }}" class="btn-action">
        <i class="fas fa-list"></i>
        Lihat Transaksi Penjualan
    </a>
</div>

<!-- Pilih Cabang / Info Cabang Section -->
@if($selectedCabang)
    <div class="chart-container" style="margin-bottom: 30px; background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); color: white; border: none;">
        <h3 class="chart-title" style="color: white; margin-bottom: 10px;">
            <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i> Lokasi Cabang Anda
        </h3>
        <div style="font-size: 24px; font-weight: bold; margin-bottom: 5px;">
            {{ $selectedCabang->nama_cabang }}
        </div>
        <div style="font-size: 15px; opacity: 0.9;">
            {{ $selectedCabang->alamat }}
        </div>
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.2); font-size: 13px;">
            <i class="fas fa-info-circle"></i> Anda sedang mengelola stok dan permintaan untuk cabang ini.
        </div>
    </div>
@else
    <div class="chart-container" style="margin-bottom: 30px;">
        <h3 class="chart-title">Pilih Cabang</h3>
        
        @if(session('error'))
            <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ffcdd2;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('dashboard') }}" method="GET" class="flex gap-4 items-center">
            <select name="cabang_id" class="form-control" onchange="this.form.submit()" style="padding: 10px; border-radius: 8px; border: 1px solid var(--border); width: 100%; max-width: 400px; background: var(--surface); color: var(--text);">
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabangList as $cabang)
                    <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                        {{ $cabang->nama_cabang }} - {{ $cabang->alamat }}
                    </option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn-action btn-action-primary">Pilih</button></noscript>
        </form>
    </div>
@endif

@if($selectedCabang)
    <!-- Stok Produk Cabang -->
    <div class="chart-container" style="margin-bottom: 30px;">
        <h3 class="chart-title">Stok Produk di Cabang: {{ $selectedCabang->nama_cabang }}</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text);">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border);">
                        <th style="padding: 12px; text-align: left;">Produk</th>
                        <th style="padding: 12px; text-align: left;">Jumlah Stok</th>
                        <th style="padding: 12px; text-align: left;">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokCabang as $stok)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;">{{ $stok->produk->nama_produk ?? '-' }}</td>
                            <td style="padding: 12px;">{{ $stok->jumlah }}</td>
                            <td style="padding: 12px;">{{ $stok->produk->satuan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 20px; text-align: center; color: var(--muted);">Tidak ada data stok untuk cabang ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Permintaan Stok -->
    <div class="chart-container" style="margin-bottom: 30px;">
        <h3 class="chart-title">Riwayat Permintaan Stok: {{ $selectedCabang->nama_cabang }}</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text);">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border);">
                        <th style="padding: 12px; text-align: left;">Tanggal</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                        <th style="padding: 12px; text-align: left;">Detail Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPermintaan as $request)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;">{{ \Carbon\Carbon::parse($request->tanggal)->format('d M Y') }}</td>
                            <td style="padding: 12px;">
                                <span class="hero-pill" style="background: {{ $request->status == 'approved' ? '#4caf50' : ($request->status == 'rejected' ? '#f44336' : '#ff9800') }}; color: white;">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($request->detail_request as $detail)
                                        <li>{{ $detail->produk->nama_produk ?? 'Produk Hapus' }} ({{ $detail->jumlah_minta }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 20px; text-align: center; color: var(--muted);">Tidak ada riwayat permintaan stok untuk cabang ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Sales Chart -->
<div class="chart-container">
    <h3 class="chart-title">Penjualan 7 Hari Terakhir</h3>
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
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan (Rp)',
                data: data,
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
@endpush
