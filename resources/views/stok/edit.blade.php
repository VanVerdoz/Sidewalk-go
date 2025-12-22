@extends('layouts.app')

@section('title', 'Edit Stok')

@push('styles')
<style>
    .form-container {
        background: var(--surface);
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--shadow-md);
        max-width: 100%;
        width: 100%;
        min-height: calc(100vh - 160px);
        padding-bottom: 160px;
        border: 1px solid var(--border);
        color: var(--text);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text);
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: var(--surface);
        color: var(--text);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
    }
    
    .form-control:disabled {
        background-color: #e9ecef;
        opacity: 1;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    @media (max-width: 768px) {
        .form-container { padding: 20px; border-radius: 16px; min-height: auto; padding-bottom: 80px; }
        .form-actions { flex-direction: column; gap: 8px; }
        .form-actions .btn { display: block; width: 100%; }
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .page-title {
        font-size: 28px;
        color: var(--text);
        font-weight: 600;
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')
<h2 class="page-title">Edit Stok</h2>

<div class="form-container">
    <form action="{{ route('stok.update', $stok->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Cabang</label>
            <input type="text" class="form-control" value="{{ $stok->cabang->nama_cabang }}" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">Produk</label>
            <input type="text" class="form-control" value="{{ $stok->produk->nama_produk }}" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $stok->jumlah) }}" min="0" required>
            @error('jumlah')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>
            <a href="{{ route('stok.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
