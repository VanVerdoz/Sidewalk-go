@extends('layouts.app')

@section('title', 'Produk per Cabang')

@section('content')
<style>
    .content-center {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 150px); /* Adjust based on header height */
    }

    .card-select-cabang {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border: 1px solid var(--border);
        background: var(--surface);
    }
    
    @media (max-width: 768px) {
        .content-center {
            padding: 20px;
            align-items: center;
            min-height: calc(100vh - 100px);
        }
        .card-select-cabang {
            box-shadow: none;
            border: none;
            background: transparent;
        }
        .card-body {
            padding: 20px !important;
        }
    }
</style>

<div class="content-center">
    <div class="card card-select-cabang">
        <div class="card-header" style="text-align: center; border-bottom: 1px solid var(--border); padding-bottom: 15px;">
            <h4 class="card-title" style="font-size: 1.5rem;">Pilih Cabang</h4>
            <p style="color: var(--muted); margin-top: 5px;">Silakan pilih cabang untuk melihat stok produk</p>
        </div>
        <div class="card-body" style="padding: 30px;">
            <div class="form-group">
                <label for="cabangSelect" class="form-label" style="font-weight: bold; margin-bottom: 10px; display: block;">Pilih berdasarkan cabang Anda</label>
                <select id="cabangSelect" class="form-control" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 16px;">
                    <option value="" selected disabled>-- Pilih Cabang --</option>
                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-top: 25px;">
                <button type="button" id="btnLanjut" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px; font-weight: 600;" disabled>
                    Lanjut <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                 <a href="{{ route('dashboard') }}" style="color: var(--muted); text-decoration: none; font-size: 14px;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                 </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('cabangSelect');
        const btn = document.getElementById('btnLanjut');
        
        select.addEventListener('change', function() {
            if (this.value) {
                btn.removeAttribute('disabled');
            } else {
                btn.setAttribute('disabled', 'true');
            }
        });

        btn.addEventListener('click', function() {
            const cabangId = select.value;
            if (cabangId) {
                const baseUrl = "{{ route('kepala.produk-raider.index') }}";
                window.location.href = baseUrl + '/' + cabangId;
            }
        });
    });
</script>
@endsection
