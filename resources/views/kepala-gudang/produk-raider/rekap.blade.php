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

@foreach($cabangs as $cabang)
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ $cabang->nama_cabang ?? ('Cabang '.$cabang->id) }}</div>
        <div class="summary">
            <div><span class="badge badge-info">Belum Laku: {{ $rekap[$cabang->id]['total_belum_laku'] }}</span></div>
            <div><span class="badge badge-info">Total Unit: {{ number_format($rekap[$cabang->id]['total_unit_belum_laku'], 0, ',', '.') }}</span></div>
        </div>
    </div>
    @if($rekap[$cabang->id]['total_belum_laku'] > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th style="text-align:center;">Jumlah Belum Laku</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap[$cabang->id]['items'] as $row)
            <tr>
                <td>
                    {{ $row['produk']->nama_produk }}
                    <span style="color: var(--muted);">({{ $row['produk']->kategori ?? '-' }})</span>
                </td>
                <td style="text-align:center;">{{ number_format($row['jumlah_belum_laku'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty">Belum ada produk belum laku hari ini untuk cabang ini.</div>
    @endif
</div>
@endforeach
@endsection
