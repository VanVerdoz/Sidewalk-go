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
        <div class="table-container">
            <table class="req-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah Stok</th>
                        @if($role === 'kepala_gudang')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokCabang as $stok)
                    <tr>
                        <td data-label="Nama Produk">{{ $stok->produk->nama_produk ?? '-' }}</td>
                        <td data-label="Harga">Rp {{ number_format($stok->produk->harga ?? 0, 0, ',', '.') }}</td>
                        <td data-label="Jumlah Stok">
                            <span style="background:var(--primary); color:white; padding:4px 10px; border-radius:4px; font-weight:bold;">
                                {{ number_format($stok->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        @if($role === 'kepala_gudang')
                        <td data-label="Aksi">
                            <div class="req-actions">
                                <a href="{{ route('stok.edit', $stok->id) }}" class="btn btn-secondary btn-small">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('stok.destroy', $stok->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus stok ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">
                                        <i class="fas fa-trash"></i> Hapus
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
