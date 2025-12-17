@extends('layouts.app')

@section('title', 'Permintaan Stok Produk')

@push('styles')
<style>
    .form-container {
        background: var(--surface);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        width: 100%;
        border: 1px solid var(--border);
        color: var(--text);
        box-sizing: border-box;
    }
    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--text);
    }
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px;
        color: var(--text);
    }
    .form-control, .form-select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        font-size: 14px;
        background: var(--surface);
        color: var(--text);
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #ff6b35;
        box-shadow: 0 0 0 2px rgba(255,107,53,0.15);
    }
    .form-text {
        font-size: 12px;
        color: var(--muted);
    }
.form-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
}

    .btn {
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(135deg,#ff6b35,#f7931e);
        color: #fff;
    }
    .btn-secondary {
        background: #6c757d;
        color: #fff;
    }
    .btn-danger {
        background: linear-gradient(135deg,#ff4d4f,#ff6b6b);
        color: #fff;
    }

    .produk-rows {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 8px;
    }

    .produk-row {
        display: flex;
        gap: 10px;
        align-items: center;
        background: var(--surface);
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 12px;
        color: var(--text);
        box-sizing: border-box;
    }

    .produk-row .produk-select {
        flex: 2;
    }

    .produk-row .jumlah-input {
        flex: 1;
    }

    .produk-row .row-action {
        flex: 0 0 auto;
    }

    .btn-small {
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 8px;
    }
    .status-badge { display:inline-block; padding:6px 10px; border-radius:10px; font-size:12px; }
    .status-pending { background: var(--surface); color:#f59e0b; border:1px solid #f59e0b; }
    .status-disetujui { background: var(--surface); color:#10b981; border:1px solid #10b981; }
    @media (max-width: 768px) {
        .page-title { font-size: 20px; margin-bottom: 12px; }
        .form-container { padding: 16px; border-radius: 14px; }
        .produk-row { flex-direction: column; align-items: stretch; gap: 8px; }
        .produk-row .produk-select, .produk-row .jumlah-input, .produk-row .row-action { flex: 1 1 auto; }
        .form-actions { flex-direction: column; gap: 8px; }
        .form-actions .btn { display: block; width: 100%; justify-content: center; }
    }
    @media (max-width: 480px) {
        .page-title { font-size: 18px; }
        .btn { padding: 8px 12px; font-size: 13px; }
    }
    @media (max-width: 640px) {
        .produk-row { gap: 6px; }
    }
</style>
@endpush

@section('content')
<h2 class="page-title">Permintaan Stok Produk</h2>
<div class="form-text" style="margin-bottom:10px;">
    Waktu WIB: <span id="waktu-wib"></span>
    <script>
        (function() {
            function updateWIB() {
                const now = new Date();
                const opts = { timeZone: 'Asia/Jakarta', hour12: false, year:'numeric', month:'2-digit', day:'2-digit', hour:'2-digit', minute:'2-digit', second:'2-digit' };
                const fmt = new Intl.DateTimeFormat('id-ID', opts).format(now);
                document.getElementById('waktu-wib').textContent = fmt + ' WIB';
            }
            updateWIB();
            setInterval(updateWIB, 1000);
        })();
    </script>
</div>

<div class="form-container">
    <form action="{{ route('raider.permintaan-stok.store') }}" method="POST">
        @csrf

	    <div class="form-group">
	        <label class="form-label">Cabang</label>
	        <select name="cabang_id" class="form-select" required>
	            <option value="">-- Pilih Cabang --</option>
                @foreach($cabang as $cb)
                <option value="{{ $cb->id }}" {{ old('cabang_id') == $cb->id ? 'selected' : '' }}>
                    {{ $cb->nama_cabang }} (Alamat: {{ $cb->alamat ?? '-' }})
                </option>
                @endforeach
	        </select>
	        @error('cabang_id')
	            <div class="form-text" style="color:red;">{{ $message }}</div>
	        @enderror
	    </div>

        <div class="form-group">
            <label class="form-label">Produk yang Dibutuhkan</label>

            <div id="produk-rows" class="produk-rows">
                <div class="produk-row">
                    <div class="produk-select">
                        <select name="produk_id[]" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nama_produk }} ({{ $item->kategori ?? 'Tanpa Kategori' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="jumlah-input">
                        <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah" required>
                    </div>
                    <div class="row-action">
                        <button type="button" class="btn btn-danger btn-small" onclick="removeProdukRow(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-small" style="margin-top: 8px;" onclick="addProdukRow()">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>

            @error('produk_id')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Catatan untuk Kepala Gudang (opsional)</label>
            <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
            @error('catatan')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
                Kirim Permintaan
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </form>
</div>


<h2 class="page-title" style="margin-top:24px;">Riwayat Permintaan Stok</h2>
<div class="form-container">
    @if(isset($riwayat) && $riwayat->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cabang</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $req)
                    @php
                        $firstDetail = optional($req->details->first());
                        $produkPertama = optional($firstDetail->produk)->nama_produk;
                        $detailCount = $req->details->count();
                        $totalQty = $req->details->sum('jumlah');
                        $st = $req->status_permintaan ?? 'pending';
                    @endphp
                    <tr>
                        <td data-label="No">{{ $loop->iteration }}</td>
                        <td data-label="Cabang">{{ optional($req->cabang)->nama_cabang ?? '-' }}</td>
                        <td data-label="Produk">
                            {{ $produkPertama ?? '-' }}
                            @if($detailCount > 1)
                                (+{{ $detailCount - 1 }} produk lain)
                            @endif
                        </td>
                        <td data-label="Jumlah">{{ number_format($totalQty ?? 0, 0, ',', '.') }} unit</td>
                        <td data-label="Tanggal">{{ \Carbon\Carbon::parse($req->dibuat_pada)->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                        <td data-label="Status">
                            <span class="status-badge {{ $st === 'disetujui' ? 'status-disetujui' : 'status-pending' }}">{{ ucfirst($st) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    @else
        <div class="form-text">Belum ada riwayat permintaan stok.</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function addProdukRow() {
        const container = document.getElementById('produk-rows');
        if (!container) return;

        const firstRow = container.querySelector('.produk-row');
        if (!firstRow) return;

        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(function (el) {
            el.value = '';
        });

        container.appendChild(newRow);
    }

    function removeProdukRow(button) {
        const row = button.closest('.produk-row');
        const container = document.getElementById('produk-rows');
        if (!row || !container) return;

        // Minimal 1 baris harus tetap ada
        if (container.children.length > 1) {
            row.remove();
        }
    }

</script>
@endpush
