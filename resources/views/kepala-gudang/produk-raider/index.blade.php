@extends('layouts.app')

@section('title', 'Produk per Cabang')

@push('styles')
<style>
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 14px; color: var(--text); }
    .branch-section { background: var(--surface); border-radius: 16px; box-shadow: var(--shadow-sm); margin-bottom: 18px; border: 1px solid var(--border); color: var(--text); }
    .branch-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); }
    .branch-title { font-size: 16px; font-weight: 600; color: var(--text); }
    .req-table { width: 100%; border-collapse: collapse; }
    .req-table th, .req-table td { padding: 12px 16px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); }
    .req-table th { background: var(--table-head); color: var(--text); text-align: left; }
    .btn-small { padding: 8px 10px; font-size: 12px; border-radius: 8px; }
    .req-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    @media (max-width: 768px) {
        .page-title { font-size: 20px; }
        .branch-header { padding: 12px 14px; }
        .branch-title { font-size: 15px; }
        .req-actions { flex-direction: column; }
        .req-actions .btn { width: 100%; justify-content: center; }
    }
    @media (max-width: 640px) {
        .req-table thead { display: none; }
        .req-table { border: 0; }
        .req-table, .req-table tbody { display: block; width: 100%; }
        .req-table tr { display: block; margin-bottom: 12px; border: 1px solid var(--border); border-radius: 12px; background: var(--surface); box-shadow: var(--shadow-sm); padding: 8px; }
        .req-table td { display: inline-block; margin: 0 4px 8px; white-space: normal; padding: 8px 10px; border-bottom: 0; vertical-align: top; }
        .req-table td[data-label="No"],
        .req-table td[data-label="Total Jenis Produk"] { width: calc(33.33% - 8px); }
        .req-table td[data-label="Cabang"],
        .req-table td[data-label="Alamat"] { width: 100%; display: block; }
        .req-actions { flex-direction: column; }
        .req-actions .btn { width: 100%; justify-content: center; }
        .req-table td[data-label="Aksi"] { display: block; width: 100%; }
        .req-table td::before { content: attr(data-label); display: block; font-weight: 600; color: var(--muted); margin-bottom: 4px; }
    }
</style>
@endpush

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
    <h2 class="page-title">Produk per Cabang</h2>
</div>

<div class="branch-section">
    <div class="branch-header">
        <div class="branch-title">Pilih Cabang</div>
    </div>
    <div style="padding:16px;">
        <div class="form-group">
            <label for="cabang-select" style="margin-bottom: 8px; display: block; font-weight: 500;">Silakan Pilih Cabang</label>
            <select id="cabang-select" class="form-control" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text);">
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                @endforeach
            </select>
            <div class="form-text" style="font-size: 12px; color: var(--muted); margin-top: 6px;">
                Silakan pilih cabang sesuai alamat anda saat ini.
            </div>
        </div>
    </div>
</div>

<div id="stok-container">
    <div style="text-align: center; padding: 40px; color: var(--muted);">
        <i class="fas fa-store" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p>Pilih cabang di atas untuk melihat daftar produk.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cabangSelect = document.getElementById('cabang-select');
        const stokContainer = document.getElementById('stok-container');
        const baseUrl = "{{ route('kepala.produk-raider.show', ':id') }}";

        cabangSelect.addEventListener('change', function() {
            const cabangId = this.value;
            
            if (!cabangId) {
                stokContainer.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: var(--muted);">
                        <i class="fas fa-store" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p>Pilih cabang di atas untuk melihat daftar produk.</p>
                    </div>
                `;
                return;
            }

            // Show loading state
            stokContainer.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary);"></i>
                    <p style="margin-top: 10px;">Memuat data produk...</p>
                </div>
            `;

            const url = baseUrl.replace(':id', cabangId);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                stokContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                stokContainer.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #ef4444;">
                        <p>Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                `;
            });
        });
    });
</script>
@endsection
