@extends('layouts.app')

@section('title', 'Manajemen Stok')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
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
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
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
</style>
@endpush

@section('content')
<div class="page-header">
    <h2 class="page-title">Manajemen Stok</h2>
    <a href="{{ route('stok.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Stok
    </a>
</div>

<div class="table-container">
    @if($stok->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Cabang</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stok as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->cabang->nama_cabang ?? '-' }}</td>
                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('stok.edit', $item->id) }}" class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('stok.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-boxes"></i>
        <h3>Belum Ada Data Stok</h3>
        <p>Silakan tambahkan data stok baru untuk memulai</p>
    </div>
    @endif
</div>
@endsection
