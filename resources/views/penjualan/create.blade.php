@extends('layouts.app')

@section('title', 'Tambah Transaksi Penjualan')

@push('styles')
<style>
    .form-container {
        background: var(--surface);
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: var(--shadow-sm);
        width: 100%;
        max-width: none;
        border: 1px solid var(--border);
        color: var(--text);
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
    .form-actions .btn {
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
    .form-actions .btn-primary {
        background: linear-gradient(135deg,#ff6b35,#f7931e);
        color: #fff;
    }
    .form-actions .btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    @media (max-width: 768px) {
        .form-container { padding: 18px; border-radius: 14px; }
        .page-title { font-size: 22px; }
    }
    @media (max-width: 640px) {
        .form-container { padding: 14px; border-radius: 12px; }
        .form-actions { flex-direction: column; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<h2 class="page-title">Tambah Transaksi Penjualan</h2>

<div class="form-container">
    <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Cabang</label>
            <select name="cabang_id" class="form-select" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabang as $cb)
                    <option value="{{ $cb->id }}" {{ old('cabang_id', $currentCabangId ?? '') == $cb->id ? 'selected' : '' }}>
                        {{ $cb->nama_cabang ?? 'Cabang '.$cb->id }} — {{ $cb->alamat ?? '-' }}
                    </option>
                @endforeach
            </select>
            @if(session('user.role') === 'raider')
                <div class="form-text">Input sesuai lokasi cabang anda saat ini.</div>
            @endif
            @error('cabang_id')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
            @error('tanggal')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <option value="tunai" {{ old('metode_pembayaran')=='tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="transfer" {{ old('metode_pembayaran')=='transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="qris" {{ old('metode_pembayaran')=='qris' ? 'selected' : '' }}>QRIS</option>
            </select>
            @error('metode_pembayaran')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Produk</label>
            <select id="produkSelect" class="form-select">
                <option value="">-- Pilih Produk --</option>
                @foreach($produk as $p)
                    <option value="{{ $p->id }}" data-kategori="{{ $p->kategori }}" data-harga="{{ $p->harga }}" data-deskripsi="{{ $p->deskripsi }}">{{ $p->nama_produk }}</option>
                @endforeach
            </select>
            <div class="form-text">Memilih produk akan otomatis mengisi kategori dan harga.</div>
        </div>

        <input type="hidden" name="produk_id" id="produkIdHidden" value="">

        <div class="form-group">
            <label class="form-label">Kategori (otomatis)</label>
            <input id="kategoriAuto" type="text" class="form-control" placeholder="-" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Harga Satuan (otomatis)</label>
            <input id="hargaAuto" type="text" class="form-control" placeholder="-" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Total Pembayaran (Rp)</label>
            <input id="totalInput" type="number" name="total" class="form-control" value="{{ old('total') }}" min="0" step="100" required readonly>
            <div class="form-text">Otomatis dihitung: Harga x Jumlah.</div>
            @error('total')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea id="keteranganField" name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <div class="form-text" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Simpan Transaksi
            </button>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@push('scripts')
<script>
    (function() {
        const sel = document.getElementById('produkSelect');
        const jumlahInput = document.getElementById('jumlahInput');
        const kategoriEl = document.getElementById('kategoriAuto');
        const hargaEl = document.getElementById('hargaAuto');
        const totalEl = document.getElementById('totalInput');
        const hid = document.getElementById('produkIdHidden');
        const ket = document.getElementById('keteranganField');

        function formatRupiah(n) {
            if (n == null || n === '') return '-';
            try {
                const num = Number(n);
                return 'Rp. ' + num.toLocaleString('id-ID');
            } catch(e) { return String(n); }
        }

        function calculateTotal() {
            const opt = sel.options[sel.selectedIndex];
            const harga = opt && opt.value ? Number(opt.dataset.harga) : 0;
            const jumlah = Number(jumlahInput.value) || 0;
            const total = harga * jumlah;
            
            if (totalEl) {
                totalEl.value = total > 0 ? total : '';
            }
        }

        sel && sel.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const kategori = opt ? opt.dataset.kategori : '';
            const harga = opt ? opt.dataset.harga : '';
            const deskripsi = opt ? opt.dataset.deskripsi : '';

            kategoriEl.value = kategori ? kategori : '-';
            hargaEl.value = harga ? formatRupiah(harga) : '-';
            
            if (hid) hid.value = opt ? opt.value : '';
            
            if (ket && (!ket.value || ket.value.trim() === '')) {
                ket.value = deskripsi || '';
            }

            calculateTotal();
        });

        jumlahInput && jumlahInput.addEventListener('input', calculateTotal);
        jumlahInput && jumlahInput.addEventListener('change', calculateTotal);

    })();
</script>
@endpush
@endsection
