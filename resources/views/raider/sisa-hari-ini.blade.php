@extends('layouts.app')

@section('title', 'Input Sisa Hari Ini')

@push('styles')
<style>
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 14px; }
    .card { background: var(--surface); border-radius: 16px; box-shadow: var(--shadow-sm); padding: 20px; border: 1px solid var(--border); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 10px 12px; border-bottom: 1px solid var(--border); }
    .table th { background: var(--table-head); text-align: left; }
    .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:16px; }
    @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<h2 class="page-title">Input Sisa Hari Ini</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="margin-bottom: 14px;">
    <form action="{{ route('raider.sisa-hari-ini.form') }}" method="GET" style="display:flex; gap:10px; align-items:center;">
        <label for="cabang_id" style="font-size:13px; color: var(--muted);">Pilih Cabang</label>
        <select name="cabang_id" id="cabang_id" onchange="this.form.submit()" style="min-width:220px;">
            <option value="">-- Pilih Cabang --</option>
            @foreach($cabangList as $cb)
                <option value="{{ $cb->id }}" {{ $selectedCabangId == $cb->id ? 'selected' : '' }}>
                    {{ $cb->nama_cabang ?? 'Cabang '.$cb->id }}
                </option>
            @endforeach
        </select>
    </form>
</div>

@if(!empty($selectedCabangId) && $stok->count() > 0)
<div class="card">
    <form action="{{ route('raider.sisa-hari-ini.store') }}" method="POST">
        @csrf
        <input type="hidden" name="cabang_id" value="{{ $selectedCabangId }}">
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th style="text-align:center;">Stok Saat Ini</th>
                    <th style="text-align:center;">Sisa Hari Ini</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stok as $item)
                <tr>
                    <td>
                        {{ $item->produk->nama_produk ?? '-' }}
                        <span style="color: var(--muted);">({{ $item->produk->kategori ?? '-' }})</span>
                        <input type="hidden" name="produk_id[]" value="{{ $item->produk_id }}">
                    </td>
                    <td style="text-align:center;">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td style="text-align:center;">
                        <input type="number" name="sisa[]" min="0" max="{{ $item->jumlah }}" value="0" style="width:100px; text-align:center;">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Sisa Hari Ini
            </button>
        </div>
    </form>
@else
    <div style="color: var(--muted);">Silakan pilih cabang untuk mengisi sisa hari ini.</div>
@endif
</div>
@endsection

