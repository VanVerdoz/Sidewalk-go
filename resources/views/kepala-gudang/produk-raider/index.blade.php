@extends('layouts.app')

@section('title', 'Produk per Cabang')

@push('styles')
<style>
    .page-title { font-size: 24px; font-weight: 600; margin-bottom: 14px; color: var(--text); }
    .branch-section { 
        background: var(--surface); 
        border-radius: 16px; 
        box-shadow: var(--shadow-sm); 
        border: 1px solid var(--border); 
        color: var(--text);
        max-width: 600px;
        margin: 0 auto; /* Center the card */
    }
    .branch-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 16px 20px; 
        border-bottom: 1px solid var(--border); 
    }
    .branch-title { font-size: 16px; font-weight: 600; color: var(--text); }
    
    .form-group { margin-bottom: 0; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text); }
    .form-control {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--bg);
        color: var(--text);
        font-size: 15px;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
    }

    #branchDetails {
        display: none; /* Hidden by default */
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        animation: fadeIn 0.3s ease-in-out;
    }

    .detail-item { margin-bottom: 12px; }
    .detail-label { font-weight: 600; color: var(--muted); font-size: 14px; display: block; margin-bottom: 4px; }
    .detail-value { font-size: 16px; color: var(--text); }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        margin-top: 16px;
        transition: opacity 0.2s;
    }
    .btn-action:hover { opacity: 0.9; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .page-title { font-size: 20px; text-align: center; }
        .branch-section { margin: 0 16px; }
    }
</style>
@endpush

@section('content')
<h2 class="page-title" style="text-align: center;">Pilih Cabang</h2>

<div class="branch-section">
    <div class="branch-header">
        <div class="branch-title">Formulir Pemilihan Cabang</div>
    </div>
    <div style="padding: 24px;">
        <div class="form-group">
            <label for="cabangSelect" class="form-label">Silakan Pilih Cabang</label>
            <select id="cabangSelect" class="form-control">
                <option value="" selected disabled>-- Pilih Cabang --</option>
                @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id }}" 
                            data-alamat="{{ $cabang->alamat ?? '-' }}" 
                            data-url="{{ route('kepala.produk-raider.show', $cabang->id) }}">
                        {{ $cabang->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="branchDetails">
            <h4 style="margin-bottom: 16px; font-size: 16px; font-weight: 600;">Keterangan Cabang</h4>
            
            <div class="detail-item">
                <span class="detail-label">Nama Cabang</span>
                <div class="detail-value" id="detailNama">-</div>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Alamat</span>
                <div class="detail-value" id="detailAlamat">-</div>
            </div>

            <a href="#" id="btnLanjut" class="btn-action">
                <i class="fas fa-box-open" style="margin-right: 8px;"></i> Lihat Produk
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('cabangSelect');
        const detailsDiv = document.getElementById('branchDetails');
        const detailNama = document.getElementById('detailNama');
        const detailAlamat = document.getElementById('detailAlamat');
        const btnLanjut = document.getElementById('btnLanjut');

        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                // Update details
                detailNama.textContent = selectedOption.text.trim();
                detailAlamat.textContent = selectedOption.getAttribute('data-alamat');
                
                // Update button link
                btnLanjut.href = selectedOption.getAttribute('data-url');
                
                // Show details section
                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
            }
        });
    });
</script>
@endsection
