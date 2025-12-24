@extends('layouts.app')

@section('title', 'Produk per Cabang')

@push('styles')
<style>
    /* Full Screen Focus Mode */
    .sidebar, .header {
        display: none !important;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
    }
    
    /* Content Layout */
    .content-center {
        width: 100%;
        max-width: 600px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .page-title { 
        font-size: 28px; 
        font-weight: 700; 
        margin-bottom: 30px; 
        color: var(--text);
        text-align: center;
    }

    .card-select-cabang { 
        background: var(--surface); 
        border-radius: 16px; 
        box-shadow: var(--shadow-md); 
        border: 1px solid var(--border); 
        color: var(--text);
        width: 100%;
        overflow: hidden;
    }

    .card-header { 
        padding: 20px 24px; 
        border-bottom: 1px solid var(--border); 
        background: var(--surface);
    }
    
    .card-title { 
        font-size: 18px; 
        font-weight: 600; 
        color: var(--text); 
        margin: 0;
    }

    .card-body {
        padding: 30px 24px;
    }
    
    .form-group { margin-bottom: 0; }
    .form-label { 
        display: block; 
        margin-bottom: 12px; 
        font-weight: 600; 
        color: var(--text); 
        font-size: 16px;
    }
    
    .form-control {
        width: 100%;
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg);
        color: var(--text);
        font-size: 15px;
        transition: all 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    #branchDetails {
        display: none;
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        animation: slideDown 0.3s ease-out;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 16px;
        border-bottom: 1px dashed var(--border);
        padding-bottom: 12px;
    }
    .detail-row:last-of-type { border-bottom: none; margin-bottom: 24px; }
    
    .detail-label { font-weight: 500; color: var(--muted); }
    .detail-value { font-weight: 600; color: var(--text); text-align: right; }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        font-size: 16px;
    }
    .btn-action:hover { 
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.25);
        color: white;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="content-center">
    <h2 class="page-title">Pilih Cabang</h2>

    <div class="card-select-cabang">
        <div class="card-header">
            <h3 class="card-title">Formulir Pemilihan Cabang</h3>
        </div>
        <div class="card-body">
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
                <div class="detail-row">
                    <span class="detail-label">Nama Cabang</span>
                    <span class="detail-value" id="detailNama">-</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Alamat</span>
                    <span class="detail-value" id="detailAlamat">-</span>
                </div>

                <a href="#" id="btnLanjut" class="btn-action">
                    Lanjut Lihat Produk <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                </a>
            </div>
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
