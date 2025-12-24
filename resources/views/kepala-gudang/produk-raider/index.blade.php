@extends('layouts.app')

@section('title', 'Produk per Cabang')

@section('content')
<div class="content">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">
            <h4 class="card-title">Pilih Cabang</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="cabangSelect" class="form-label" style="font-weight: bold; margin-bottom: 10px; display: block;">Pilih berdasarkan cabang Anda</label>
                <select id="cabangSelect" class="form-control" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text);">
                    <option value="" selected disabled>-- Pilih Cabang --</option>
                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-top: 20px; text-align: right;">
                <button type="button" id="btnLanjut" class="btn btn-primary" disabled>
                    Lanjut <i class="fas fa-arrow-right"></i>
                </button>
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
                // Construct URL manually since route() helper is PHP-side
                // Base pattern: /kepala-gudang/produk-raider/{id}
                const baseUrl = "{{ route('kepala.produk-raider.index') }}";
                window.location.href = baseUrl + '/' + cabangId;
            }
        });
    });
</script>
@endsection
