@extends('layouts.app')

@section('title', 'Rekapan Sisa Hari Ini')

@push('styles')
<style>
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 14px; }
    .card { background: var(--surface); border-radius: 16px; box-shadow: var(--shadow-sm); padding: 16px; border: 1px solid var(--border); margin-bottom: 16px; }
    .card-header { display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-bottom: 12px; }
    .card-title { font-size: 16px; font-weight: 600; }
    .summary { display:flex; gap:16px; color: var(--muted); font-size: 13px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 10px 12px; border-bottom: 1px solid var(--border); }
    .table th { background: var(--table-head); text-align: left; }
    @media (max-width: 640px) { .summary { flex-direction: column; gap:8px; } }
    .badge { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; }
    .badge-info { background: #e0f2fe; color:#0369a1; }
    .empty { color: var(--muted); padding:10px 0; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
    <h2 class="page-title">Rekapan Sisa Hari Ini ({{ $tanggal }})</h2>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('kepala.produk-raider.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Produk per Cabang
        </a>
    </div>
</div>

<div class="card" style="margin-bottom: 14px;">
    <form action="{{ route('kepala.rekap-sisa-hari-ini') }}" method="GET" style="display:flex; gap:10px; align-items:center;">
        <label for="cabang_id" style="font-size:13px; color: var(--muted);">Pilih Cabang</label>
        <select name="cabang_id" id="cabang_id" onchange="this.form.submit()" style="min-width:220px;">
            <option value="">-- Pilih Cabang --</option>
            @foreach($cabangs as $cb)
                <option value="{{ $cb->id }}" {{ $selectedCabangId == $cb->id ? 'selected' : '' }}>
                    {{ $cb->nama_cabang ?? 'Cabang '.$cb->id }}
                </option>
            @endforeach
        </select>
    </form>
</div>

@if(!empty($selectedCabangId) && $selectedCabang)
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ $selectedCabang->nama_cabang ?? ('Cabang '.$selectedCabang->id) }}</div>
        <div class="summary">
            <div><span class="badge badge-info">Total Produk: {{ $totalProduk }}</span></div>
            <div><span class="badge badge-info">Total Sisa: {{ number_format($totalUnitSisa, 0, ',', '.') }}</span></div>
        </div>
    </div>
    @if($items->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th style="text-align:center;">Jumlah Produk Tersisa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $row)
            <tr>
                <td>
                    {{ $row['produk']->nama_produk }}
                    <span style="color: var(--muted);">({{ $row['produk']->kategori ?? '-' }})</span>
                </td>
                <td style="text-align:center;">{{ number_format($row['sisa'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty">Belum ada stok produk untuk cabang ini.</div>
    @endif
</div>
@else
<div class="empty">Silakan pilih cabang untuk melihat rekapan sisa hari ini.</div>
@endif
@endsection
