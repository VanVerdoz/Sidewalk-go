@extends('layouts.app')

@section('title', 'Produk per Cabang')

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Produk per Cabang</h4>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Cabang</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cabangs as $cabang)
                        <tr>
                            <td>{{ $cabang->nama_cabang }}</td>
                            <td>{{ $cabang->alamat }}</td>
                            <td class="table-actions">
                                <a href="{{ route('kepala.produk-raider.show', $cabang->id) }}" class="btn btn-small btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('kepala.produk-raider.create', $cabang->id) }}" class="btn btn-small btn-success">
                                    <i class="fas fa-paper-plane"></i> Kirim Stok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data cabang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
