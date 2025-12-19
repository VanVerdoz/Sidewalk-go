@extends('layouts.app')

@section('title', 'Detail Permintaan Produk')

@push('styles')
<style>
.page-title { font-size: 24px; font-weight: 600; margin-bottom: 12px; color: var(--text); }
.detail-card { background:#fff; border:1px solid #eef0f3; border-radius:18px; padding:18px; box-shadow:0 10px 18px rgba(0,0,0,0.06); color:#1f2937; }
.dark .detail-card { background: var(--surface); border-color: var(--border); color: var(--text); box-shadow: var(--shadow-sm); }
.grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-top:8px; }
.item { background: #fafafa; border:1px solid #eef0f3; border-radius:12px; padding:12px; }
.dark .item { background: var(--surface); border-color: var(--border); }
.label { font-size:12px; color:#6b7280; margin-bottom:4px; }
.dark .label { color: var(--muted); }
.value { font-weight:600; }
.actions { margin-top:16px; display:flex; gap:10px; }
.btn { padding:10px 14px; border:none; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
.btn-approve { background:#10b981; color:#fff; }
.btn-reject { background:#ef4444; color:#fff; }
.products { margin-top:16px; }
.prod-row { display:flex; justify-content:space-between; padding:10px 12px; border-bottom:1px dashed #e5e7eb; }
.dark .prod-row { border-color: var(--border); }
.prod-name { font-weight:600; }
.prod-qty { color:#6b7280; }
</style>
@endpush

@section('content')
<h2 class="page-title">Detail Permintaan Produk</h2>

<div class="detail-card">
    <div class="grid">
        <div class="item">
            <div class="label">Rider</div>
            <div class="value">{{ optional($req->raider)->nama_lengkap ?? optional($req->raider)->username ?? '-' }}</div>
        </div>
        <div class="item">
            <div class="label">Cabang</div>
            <div class="value">{{ optional($req->cabang)->nama_cabang ?? '-' }}</div>
        </div>
        <div class="item">
            <div class="label">Tanggal permintaan</div>
            <div class="value">{{ \Carbon\Carbon::parse($req->tanggal)->format('d/m/Y H:i') }}</div>
        </div>
        <div class="item">
            <div class="label">Status</div>
            <div class="value">{{ ucfirst($req->status ?? 'pending') }}</div>
        </div>
    </div>

    <div class="grid" style="margin-top:12px;">
        <div class="item" style="grid-column: 1 / -1;">
            <div class="label">Catatan Rider</div>
            <div class="value">{{ $req->catatan ?? '-' }}</div>
        </div>
    </div>

    <div class="products">
        @forelse($req->details as $d)
            <div class="prod-row">
                <div class="prod-name">{{ optional($d->produk)->nama_produk ?? '-' }}</div>
                <div class="prod-qty">Jumlah: {{ number_format($d->jumlah ?? 0, 0, ',', '.') }}</div>
            </div>
        @empty
            <div class="form-text">Tidak ada produk pada permintaan ini.</div>
        @endforelse
    </div>

    <div class="actions">
        <form action="{{ route('kepala.permintaan-stok.approve', $req->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-approve"><i class="fas fa-check"></i> Setujui</button>
        </form>
        <form action="{{ route('kepala.permintaan-stok.reject', $req->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-reject"><i class="fas fa-times"></i> Tolak</button>
        </form>
    </div>
</div>
@endsection

