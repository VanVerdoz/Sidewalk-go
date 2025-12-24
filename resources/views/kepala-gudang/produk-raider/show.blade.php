@extends('layouts.app')

@section('title', 'Stok Cabang - ' . $cabang->nama_cabang)

@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4" style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
        <a href="{{ route('kepala.produk-raider.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('kepala.produk-raider.create', $cabang->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Stok Cabang
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Stok Tersedia di {{ $cabang->nama_cabang }}</h4>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokCabang as $stok)
                        <tr>
                            <td>{{ $stok->produk->nama_produk ?? '-' }}</td>
                            <td>Rp {{ number_format($stok->produk->harga ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <span style="background:var(--primary); color:white; padding:4px 10px; border-radius:4px; font-weight:bold;">
                                    {{ $stok->jumlah }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada stok di cabang ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
