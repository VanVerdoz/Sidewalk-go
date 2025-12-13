@extends('layouts.app')

@section('title', 'Produk Terjual')

@push('styles')
<style>
    .table-container { background: var(--surface); padding: 30px; border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border); }
    .data-table { width:100%; border-collapse: collapse; }
    .data-table thead th { text-align:left; padding:12px; font-weight:600; border-bottom:1px solid var(--border); color: var(--text); }
    .data-table tbody td { padding:12px; border-bottom:1px solid var(--border); color: var(--text); }
    .empty { text-align:center; color: var(--muted); padding:18px; }
    .chart-canvas { height: 280px; }
</style>
@endpush

@section('content')
<h1 class="page-title" style="font-size:22px; color: var(--text); margin-bottom: 10px;">Produk Terjual</h1>
<p class="dashboard-subtitle" style="color: var(--muted); margin-bottom: 20px;">Daftar transaksi produk terjual oleh Raider</p>

<div class="table-container" style="margin-bottom:16px;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div style="font-weight:600; color: var(--text);">Filter Cabang</div>
        <form action="{{ route('kepala.produk-terjual') }}" method="GET" style="display:flex; align-items:center; gap:10px;">
            <label for="cabang_id" style="font-size:13px; color: var(--muted);">Pilih Cabang</label>
            <select name="cabang_id" id="cabang_id" onchange="this.form.submit()" style="min-width: 220px;">
                <option value="">Semua Cabang</option>
                @foreach($cabangList as $cb)
                    <option value="{{ $cb->id }}" {{ (string)$selectedCabangId === (string)$cb->id ? 'selected' : '' }}>
                        {{ $cb->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </form>
        <button type="button" class="btn btn-secondary" onclick="toggleRekap()">Rekap Harian Produk Terjual</button>
    </div>
</div>

<div class="table-container" id="rekap-produk-terjual" style="margin-bottom:16px; display:none;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
        <div style="font-weight:600; color: var(--text);">Rekap Harian Produk Terjual</div>
        <div style="color: var(--muted);">
            Top 3 Terlaris:
            @if(($top3Terlaris ?? collect())->isNotEmpty())
                @foreach($top3Terlaris as $idx => $row)
                    <span style="font-weight:600; color: var(--text);">
                        {{ optional($row->produk)->nama_produk ?? '-' }}
                    </span>
                    <span style="color: var(--primary);">
                        (Jumlah Terjual {{ (int)($row->qty ?? 0) }})
                    </span>{{ $idx < count($top3Terlaris)-1 ? ', ' : '' }}
                @endforeach
            @else
                <span>Tidak ada</span>
            @endif
        </div>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapProdukHariIni as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ optional($row->produk)->nama_produk ?? '-' }}</td>
                    <td>{{ (int)($row->qty ?? 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="chart-canvas" style="margin-top:14px;">
        <canvas id="top3Pie"></canvas>
    </div>
    <div id="top3Legend" style="margin-top:8px; font-size: 12px; color: var(--muted);"></div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Metode Pembayaran</th>
                <th>Raider</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($row->produk)->nama_produk ?? '-' }}</td>
                    <td>{{ optional($row->penjualan)->tanggal ? \Carbon\Carbon::parse($row->penjualan->tanggal)->format('d/m/Y') : '-' }}</td>
                    <td>{{ optional($row->penjualan)->metode_pembayaran ?? '-' }}</td>
                    <td>{{ optional(optional($row->penjualan)->pengguna)->nama_lengkap ?? '-' }}</td>
                    <td>{{ optional($row->produk)->deskripsi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">Belum ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleRekap() {
        var el = document.getElementById('rekap-produk-terjual');
        if (!el) return;
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
    (function() {
        var ctx = document.getElementById('top3Pie');
        if (!ctx) return;
        @php
            $top3Labels = ($top3Terlaris ?? collect())->map(function($r){ return optional($r->produk)->nama_produk ?? '-'; })->values();
            $top3Data = ($top3Terlaris ?? collect())->map(function($r){ return (int)($r->qty ?? 0); })->values();
        @endphp
        var labels = @json($top3Labels);
        var data = @json($top3Data);
        if (!labels || labels.length === 0) return;
        var colors = ['#ff6b35', '#4c6fff', '#28c76f'];
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
        var legend = document.getElementById('top3Legend');
        if (legend) {
            var html = labels.map(function(label, i) {
                var c = colors[i];
                return '<span style="display:inline-flex; align-items:center; margin-right:12px;">'
                    + '<span style="width:10px;height:10px;background:'+c+';border-radius:50%;display:inline-block;margin-right:6px;"></span>'
                    + label + '</span>';
            }).join('');
            legend.innerHTML = html;
        }
    })();
</script>
@endpush
