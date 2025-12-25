@extends('layouts.app')

@section('title', 'Rekapan Sisa Hari Ini')

@push('styles')
<style>
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 14px; }
    .page-header { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
    .card { background: var(--surface); border-radius: 16px; box-shadow: var(--shadow-sm); padding: 16px; border: 1px solid var(--border); margin-bottom: 16px; }
    .card-header { display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-bottom: 12px; flex-wrap: wrap; gap: 10px; }
    .card-title { font-size: 16px; font-weight: 600; }
    .summary { display:flex; gap:10px; color: var(--muted); font-size: 13px; flex-wrap: wrap; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table { width: 100%; border-collapse: collapse; min-width: 300px; }
    .table th, .table td { padding: 10px 12px; border-bottom: 1px solid var(--border); white-space: nowrap; }
    .table th { background: var(--table-head); text-align: left; }
    .badge { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; white-space: nowrap; }
    .badge-info { background: #e0f2fe; color:#0369a1; }
    .empty { color: var(--muted); padding:10px 0; }
    .form-filter { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .form-filter select { min-width: 220px; flex: 1; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); }
    
    @media (min-width: 768px) {
        .page-header { flex-direction: row; justify-content: space-between; align-items: center; }
        .page-title { margin-bottom: 0; }
        .form-filter { max-width: 400px; }
    }

    @media (max-width: 640px) {
        .page-title { font-size: 20px; }
        .summary { flex-direction: row; gap: 8px; width: 100%; }
        .summary > div { flex: 1; }
        .badge { width: 100%; text-align: center; }
        .page-header .btn { width: 100%; justify-content: center; }
        .card-header { flex-direction: column; align-items: flex-start; }
        .card-title { margin-bottom: 8px; }
        
        /* Mobile Card View for Table */
        .table thead { display: none; }
        .table tbody tr { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: var(--surface); 
            border: 1px solid var(--border); 
            border-radius: 12px; 
            margin-bottom: 12px; 
            padding: 16px; 
            box-shadow: var(--shadow-sm); 
        }
        .table td { border: none; padding: 0; white-space: normal; }
        .table td:first-child { 
            flex: 1; 
            font-weight: 600; 
            text-align: left; 
            margin-bottom: 0; 
            padding-bottom: 0; 
            border-bottom: none; 
            padding-right: 12px;
        }
        .table td:last-child { 
            display: flex; 
            align-items: center; 
            justify-content: flex-end; 
            color: #ff6b35; /* Orange color */
            font-weight: 700; 
            font-size: 16px; 
            flex-shrink: 0;
        }
        .table td::before { display: none; }
        .table td:last-child::before { 
            content: "Sisa:"; 
            font-weight: normal; 
            color: var(--muted); 
            font-size: 13px; 
            margin-right: 6px; 
            display: inline-block;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h2 class="page-title">Rekapan Sisa Hari Ini <span style="display:block; font-size:14px; color:var(--muted); font-weight:400; margin-top:4px;">{{ $tanggal }}</span></h2>
    <div>
        <a href="{{ route('kepala.produk-raider.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
        </a>
    </div>
</div>

<div class="card">
    <form action="{{ route('kepala.rekap-sisa-hari-ini') }}" method="GET" class="form-filter">
        <label for="cabang_id" style="font-size:13px; color: var(--muted); display:none;">Pilih Cabang</label>
        <select name="cabang_id" id="cabang_id" onchange="this.form.submit()">
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
    <div class="table-responsive">
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
                        <span style="color: var(--muted); font-size:12px; display:block;">{{ $row['produk']->kategori ?? '-' }}</span>
                    </td>
                    <td style="text-align:center;" data-label="Sisa:">
                        {{ number_format($row['sisa'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty">Belum ada stok produk untuk cabang ini.</div>
    @endif
</div>
@else
<div class="empty" style="text-align:center; padding:40px 20px;">
    <i class="fas fa-store" style="font-size:48px; color:var(--border); margin-bottom:16px;"></i>
    <p>Silakan pilih cabang untuk melihat rekapan sisa hari ini.</p>
</div>
@endif
@endsection
