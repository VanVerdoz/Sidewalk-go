@extends('layouts.app')

@section('title', 'Stok Cabang - ' . $cabang->nama_cabang)

@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4" style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
        <a href="{{ route('kepala.produk-raider.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @php $role = strtolower(trim(session('user.role') ?? '')); @endphp
        @if($role === 'kepala_gudang')
        <a href="{{ route('kepala.produk-raider.create', $cabang->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Stok Cabang
        </a>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Stok Tersedia di {{ $cabang->nama_cabang }}</h4>
        </div>
        <div class="card-body">
            @if(isset($unsoldProducts) && $unsoldProducts->count() > 0)
            <div class="alert alert-info" style="margin-bottom: 12px;">
                Ada {{ $unsoldProducts->count() }} produk belum laku hari ini:
                {{ implode(', ', $unsoldProducts->all()) }}
            </div>
            @endif
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th style="text-align:center;">Jumlah Stok</th>
                            <th style="text-align:center;">Sisa Hari Ini</th>
                            @if($role === 'kepala_gudang')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokCabang as $stok)
                        <tr>
                            <td>
                                {{ $stok->produk->nama_produk ?? '-' }}
                                <span style="color: var(--muted); margin-left: 6px;">({{ $stok->produk->kategori ?? '-' }})</span>
                            </td>
                            <td>Rp {{ number_format($stok->produk->harga ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:center;">
                                {{ number_format($stok->jumlah, 0, ',', '.') }}
                            </td>
                            <td style="text-align:center;">
                                {{ number_format($stok->sisa_hari_ini ?? 0, 0, ',', '.') }}
                            </td>
                            @if($role === 'kepala_gudang')
                            <td>
                                <div class="action-buttons" style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('stok.edit', $stok->id) }}" class="btn btn-sm btn-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('stok.destroy', $stok->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus stok ini?')" title="Hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $role === 'kepala_gudang' ? 4 : 3 }}" class="text-center">Belum ada stok di cabang ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
