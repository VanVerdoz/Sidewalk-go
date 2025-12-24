@extends('layouts.app')

@section('title', 'Riwayat Stok - ' . $cabang->nama_cabang)

@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4" style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
        <a href="{{ route('kepala.produk-raider.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('kepala.produk-raider.create', $cabang->id) }}" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Kirim Stok Baru
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat Pengiriman Stok ke {{ $cabang->nama_cabang }}</h4>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th>Detail Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $req)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($req->tanggal)->format('d M Y H:i') }}</td>
                            <td>{{ $req->catatan }}</td>
                            <td>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($req->details as $detail)
                                    <li>
                                        {{ $detail->produk->nama_produk ?? '-' }} 
                                        <span style="background:var(--primary); color:white; padding:2px 6px; border-radius:4px; font-size:11px;">{{ $detail->jumlah }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada riwayat pengiriman stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
