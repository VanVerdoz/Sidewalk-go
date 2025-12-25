<div class="card" style="margin-top: 20px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h4 class="card-title" style="margin: 0;">Stok Tersedia di {{ $cabang->nama_cabang }}</h4>
        @php $role = strtolower(trim(session('user.role') ?? '')); @endphp
        @if($role === 'kepala_gudang')
        <a href="{{ route('kepala.produk-raider.create', $cabang->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Stok
        </a>
        @endif
    </div>
    <div class="card-body">
        @if(isset($unsoldProducts) && $unsoldProducts->count() > 0)
        <div class="alert alert-info" style="margin-bottom: 12px;">
            Ada {{ $unsoldProducts->count() }} produk belum laku hari ini:
            {{ implode(', ', $unsoldProducts->all()) }}
        </div>
        @endif
        <div class="table-container">
            <table class="req-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th style="text-align: center;">Jumlah Stok</th>
                        <th style="text-align: center;">Sisa Hari Ini</th>
                        @if($role === 'kepala_gudang')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokCabang as $stok)
                    <tr>
                        <td data-label="Nama Produk">
                            {{ $stok->produk->nama_produk ?? '-' }}
                            <span style="color: var(--muted); margin-left: 6px;">({{ $stok->produk->kategori ?? '-' }})</span>
                        </td>
                        <td data-label="Harga">Rp {{ number_format($stok->produk->harga ?? 0, 0, ',', '.') }}</td>
                        <td data-label="Jumlah Stok" style="text-align: center;">
                            {{ number_format($stok->jumlah, 0, ',', '.') }}
                        </td>
                        <td data-label="Sisa Hari Ini" style="text-align: center;">
                            {{ number_format($stok->sisa_hari_ini ?? 0, 0, ',', '.') }}
                        </td>
                        @if($role === 'kepala_gudang')
                        <td data-label="Aksi">
                            <div class="req-actions">
                                <a href="{{ route('stok.edit', $stok->id) }}" class="btn btn-secondary btn-small" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('stok.destroy', $stok->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus stok ini?');" title="Hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $role === 'kepala_gudang' ? 4 : 3 }}" class="text-center" style="text-align: center;">Belum ada stok di cabang ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
