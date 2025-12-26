@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .page-title {
        font-size: 28px;
        color: var(--text);
        font-weight: 600;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #ffffff;
        color: var(--text);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-sm {
        padding: 8px 15px;
        font-size: 13px;
    }

    .btn-edit {
        background: #4CAF50;
        color: white;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .table-container {
        background: var(--surface);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        color: var(--text);
    }

    .rekap-container {
        margin-bottom: 20px;
        background: var(--surface);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--border);
        color: var(--text);
    }

    .rekap-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text);
    }

    .rekap-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
    }

    .rekap-item-label {
        font-size: 13px;
        color: var(--muted);
    }

    .rekap-item-value {
        font-size: 18px;
        font-weight: 600;
        color: #ff6b35;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
    }

    .table thead th {
        padding: 15px;
        text-align: left;
        font-weight: 500;
        font-size: 14px;
    }

    .table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s;
    }

    .table tbody tr:hover {
        background: var(--table-hover);
    }

    .table tbody td {
        padding: 15px;
        font-size: 14px;
        color: var(--text);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 42px;
        height: 42px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        line-height: 1;
    }
    .btn-icon i { font-size: 16px; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 14px;
    }

    

    /* Custom Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: var(--surface);
        padding: 30px;
        border-radius: 20px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        animation: modalSlideIn 0.3s ease;
        border: 1px solid var(--border);
        color: var(--text);
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-icon {
        font-size: 64px;
        color: #f44336;
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 10px;
    }

    .modal-message {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 30px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .page-title { font-size: 22px; }
        .header-actions { justify-content: flex-start; }
        .table-container { padding: 16px; border-radius: 16px; }
        .rekap-grid { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: row; }
        .action-buttons .btn { width: auto; justify-content: center; }
    }
    @media (max-width: 640px) {
        .table-container { margin: 0; width: 100%; border-radius: 0; padding: 12px; }
        .rekap-container { margin: 0; width: 100%; border-radius: 0; padding-left: 12px; padding-right: 12px; }
        .table-container { overflow-x: hidden; }
        .transaksi-table thead { display: none; }
        .transaksi-table { border: 0; border-radius: 0; box-shadow: none; background: transparent; overflow: visible !important; }
        .transaksi-table, .transaksi-table tbody { display: block; width: 100%; }
        .transaksi-table tr {
            display: block;
            gap: 0;
            margin-bottom: 8px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface);
            box-shadow: var(--shadow-sm);
            padding: 10px 12px;
            overflow: visible;
            max-width: 100%;
        }
        .transaksi-table td {
            display: grid;
            grid-template-columns: 110px 1fr;
            column-gap: 8px;
            align-items: start;
            margin: 0 0 8px 0;
            white-space: normal;
            word-break: normal;
            overflow-wrap: break-word;
            padding: 0;
            border-bottom: 0;
            font-size: 12px;
            line-height: 1.4;
            vertical-align: top;
            width: 100%;
        }
        .transaksi-table td[data-label="Aksi"] {
            white-space: nowrap;
        }
        .transaksi-table td::before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: var(--muted);
        }
        .action-buttons { display:flex; gap: 8px; flex-wrap: nowrap; align-items: center; }
        .btn-icon { width: 36px; height: 36px; }
        .btn-sm { padding: 5px 8px; font-size: 11px; }
    }
    @media (max-width: 480px) {
        .page-title { font-size: 20px; }
        .btn { padding: 10px 16px; font-size: 13px; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h2 class="page-title">Transaksi Penjualan</h2>
    <div class="header-actions">
        @if(in_array(session('user.role'), ['admin','owner']))
        <form action="{{ route('penjualan.index') }}" method="GET" style="display:flex; align-items:center; gap:10px;">
            <label for="cabang_id" style="font-size:13px; color: var(--muted);">Pilih Cabang</label>
            <select name="cabang_id" id="cabang_id" onchange="this.form.submit()" style="min-width: 220px;">
                <option value="">Semua Cabang</option>
                @foreach(($cabangList ?? collect()) as $cb)
                    <option value="{{ $cb->id }}" {{ (string)($selectedCabangId ?? '') === (string)$cb->id ? 'selected' : '' }}>
                        {{ $cb->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </form>
        @endif
        @if(in_array(session('user.role'), ['raider']))
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Transaksi
        </a>
        @if(isset($totalPendapatanHariIni) && isset($totalProdukHariIni))
        <button type="button" class="btn btn-secondary" onclick="toggleRekap()">
            <i class="fas fa-chart-line"></i>
            Rekap Laporan Penjualan / Hari Ini
        </button>
        @endif
        @if(!empty($monitorWarningCabang))
        <div style="flex-basis: 100%; margin-top: 8px; background: #fee2e2; color: #7f1d1d; border: 1px solid #fecaca; border-radius: 10px; padding: 10px 12px;">
            <i class="fas fa-exclamation-triangle"></i>
            <span style="margin-left:6px;">{{ $monitorWarningCabang }}</span>
        </div>
        @elseif(!empty($monitorOkCabang))
        <span style="display:inline-flex; align-items:center; gap:6px; margin-top:8px; background:#dcfce7; color:#14532d; border:1px solid #bbf7d0; border-radius:999px; padding:4px 10px; font-size:12px;">
            <i class="fas fa-check-circle"></i>
            <span>{{ $monitorOkCabang }}</span>
        </span>
        @endif
        @endif
    </div>
</div>

<div class="table-container">
    @if(in_array(session('user.role'), ['admin','owner']))
    <div class="rekap-container" style="margin-bottom: 20px;">
        @if(empty($selectedCabangId))
            <div class="rekap-title">
                Rekap Transaksi Harian (Semua Cabang) - {{ now()->format('d/m/Y') }}
            </div>
            <div class="rekap-grid">
                <div>
                    <div class="rekap-item-label">Total Pendapatan</div>
                    <div class="rekap-item-value">Rp. {{ number_format($grandTotalHariIni ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        @else
            <div class="rekap-title">
                Rekap Transaksi Harian - {{ now()->format('d/m/Y') }} | Cabang: {{ optional(($cabangList ?? collect())->firstWhere('id', $selectedCabangId))->nama_cabang ?? '-' }}
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Cabang</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($rekapCabangHariIni ?? collect()) as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($row->cabang)->nama_cabang ?? '-' }}</td>
                            <td>{{ number_format((int)($row->transaksi ?? 0), 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format((int)($row->total ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color: var(--muted);">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
    @endif
    @if(session('user.role') === 'raider' && isset($totalPendapatanHariIni) && isset($totalProdukHariIni))
    <div id="rekap-panel" class="rekap-container" style="display: none;">
        <div class="rekap-title">Rekap Penjualan Hari Ini ({{ now()->format('d/m/Y') }})</div>
        <div class="rekap-grid">
            <div>
                <div class="rekap-item-label">Nama Cabang</div>
                <div class="rekap-item-value">{{ optional($penjualan->first()->cabang ?? null)->nama_cabang ?? '-' }}</div>
            </div>
            <div>
                <div class="rekap-item-label">Alamat Cabang</div>
                <div class="rekap-item-value">{{ optional($penjualan->first()->cabang ?? null)->alamat ?? '-' }}</div>
            </div>
            <div>
                <div class="rekap-item-label">Total Produk Terjual</div>
                <div class="rekap-item-value">{{ number_format($totalProdukHariIni ?? 0, 0, ',', '.') }} pcs</div>
            </div>
            <div>
                <div class="rekap-item-label">Total Pendapatan Raider Hari Ini</div>
                <div class="rekap-item-value">Rp. {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($penjualan->count() > 0)
    <table class="table transaksi-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Cabang</th>
                <th>Nama Lengkap</th>
                <th>Metode Pembayaran</th>
                <th>Nama Produk</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
            @php
                $produkHargaList = $item->detail_penjualan->map(function($detail) {
                    $nama = $detail->produk->nama_produk ?? null;
                    if (!$nama) return null;
                    return $nama.' (Rp. '.number_format($detail->harga ?? 0, 0, ',', '.').')';
                })->filter()->values();
            @endphp
            <tr>
                <td data-label="No">{{ $index + 1 }}</td>
                <td data-label="Cabang">{{ $item->cabang->nama_cabang ?? '-' }}</td>
                <td data-label="Nama Lengkap">{{ $item->pengguna->nama_lengkap ?? ($item->pengguna->username ?? '-') }}</td>
                <td data-label="Metode Pembayaran">{{ ucfirst($item->metode_pembayaran ?? '-') }}</td>
                <td data-label="Nama Produk">
                    @if($produkHargaList->count() === 0)
                        -
                    @else
                        {{ implode(', ', $produkHargaList->toArray()) }}
                    @endif
                </td>
                <td data-label="Total Harga">Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                <td data-label="Aksi">
                    <div class="action-buttons">
                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-icon btn-primary" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(in_array(session('user.role'), ['raider']))
                        <a href="{{ route('penjualan.edit', $item->id) }}" class="btn btn-icon btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('penjualan.destroy', $item->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-icon btn-delete" onclick="confirmDelete({{ $item->id }}, '{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h3>Belum Ada Transaksi</h3>
        <p>Silakan tambahkan transaksi baru untuk memulai</p>
    </div>
    @endif
</div>

<!-- Custom Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p class="modal-message" id="deleteMessage">Apakah Anda yakin ingin menghapus transaksi ini?</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-danger" onclick="submitDelete()">
                <i class="fas fa-check"></i>
                Ya, Hapus
            </button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">
                <i class="fas fa-times"></i>
                Batal
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let deleteFormId = null;

    function confirmDelete(id, tanggal) {
        deleteFormId = id;
        document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus transaksi tanggal "${tanggal}"?`;
        document.getElementById('deleteModal').classList.add('active');
    }

    function submitDelete() {
        if (deleteFormId) {
            document.getElementById('delete-form-' + deleteFormId).submit();
        }
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.remove('active');
        deleteFormId = null;
    }

    function toggleRekap() {
        const panel = document.getElementById('rekap-panel');
        if (!panel) return;
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush
@endsection
