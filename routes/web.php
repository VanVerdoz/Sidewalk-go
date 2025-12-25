<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\RequestStokController;

// Semua routes menggunakan middleware 'web' untuk session & CSRF
Route::middleware(['web'])->group(function () {

    // Redirect root ke halaman login Blade (seperti sebelumnya)
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Auth Routes
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware(['web.auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Routes
        Route::get('/profile', [WebAuthController::class, 'showProfile'])->name('profile.show');
        Route::put('/profile', [WebAuthController::class, 'updateProfile'])->name('profile.update');

        // Produk Routes
        Route::resource('produk', \App\Http\Controllers\Web\ProdukController::class);

        // Stok Routes
        Route::resource('stok', \App\Http\Controllers\Web\StokController::class);

        // Penjualan Routes
        Route::resource('penjualan', \App\Http\Controllers\Web\PenjualanController::class);

        // Laporan Keuangan Routes
        Route::resource('laporan-keuangan', \App\Http\Controllers\Web\LaporanKeuanganController::class);

        // Cabang Routes
        Route::resource('cabang', \App\Http\Controllers\Web\CabangController::class);

        // Pengguna Routes
        Route::resource('pengguna', \App\Http\Controllers\Web\PenggunaController::class);

        // Permintaan stok oleh Raider
        Route::get('/raider/permintaan-stok', [RequestStokController::class, 'create'])->name('raider.permintaan-stok.create');
        Route::post('/raider/permintaan-stok', [RequestStokController::class, 'store'])->name('raider.permintaan-stok.store');

        // Kepala Gudang: daftar permintaan stok raider
        Route::get('/kepala-gudang/permintaan-stok', [RequestStokController::class, 'index'])->name('kepala.permintaan-stok.index');
        // Hierarchical view
        Route::get('/kepala-gudang/permintaan-stok/cabang/{cabangId}', [RequestStokController::class, 'cabangView'])->name('kepala.permintaan-stok.cabang');
        Route::get('/kepala-gudang/permintaan-stok/cabang/{cabangId}/rider/{raiderId}', [RequestStokController::class, 'riderView'])->name('kepala.permintaan-stok.rider');
        Route::get('/kepala-gudang/permintaan-stok/{permintaanId}', [RequestStokController::class, 'detailView'])->name('kepala.permintaan-stok.detail');

        // Kepala Gudang: Produk Terjual (halaman terpisah)
        Route::get('/kepala-gudang/produk-terjual', [DashboardController::class, 'kepalaGudangProdukTerjual'])->name('kepala.produk-terjual');

        // Kepala Gudang: Produk Raider (Kirim Stok ke Raider)
        Route::get('/kepala-gudang/produk-raider', [\App\Http\Controllers\Web\ProdukRaiderController::class, 'index'])->name('kepala.produk-raider.index');
        Route::get('/kepala-gudang/produk-raider/{cabangId}', [\App\Http\Controllers\Web\ProdukRaiderController::class, 'show'])->name('kepala.produk-raider.show');
        Route::get('/kepala-gudang/produk-raider/{cabangId}/create', [\App\Http\Controllers\Web\ProdukRaiderController::class, 'create'])->name('kepala.produk-raider.create');
        Route::post('/kepala-gudang/produk-raider/{cabangId}', [\App\Http\Controllers\Web\ProdukRaiderController::class, 'store'])->name('kepala.produk-raider.store');
        Route::get('/kepala-gudang/rekap-sisa-hari-ini', [\App\Http\Controllers\Web\ProdukRaiderController::class, 'rekap'])->name('kepala.rekap-sisa-hari-ini');

        // Raider: Input Sisa Hari Ini
        Route::get('/raider/sisa-hari-ini', [\App\Http\Controllers\Web\PenjualanController::class, 'sisaHariIniForm'])->name('raider.sisa-hari-ini.form');
        Route::post('/raider/sisa-hari-ini', [\App\Http\Controllers\Web\PenjualanController::class, 'sisaHariIniStore'])->name('raider.sisa-hari-ini.store');

        // Aksi Kepala Gudang terhadap permintaan stok raider
        Route::post('/kepala-gudang/permintaan-stok/{id}/approve', [RequestStokController::class, 'approve'])->name('kepala.permintaan-stok.approve');
        Route::post('/kepala-gudang/permintaan-stok/{id}/pending', [RequestStokController::class, 'pending'])->name('kepala.permintaan-stok.pending');
        Route::post('/kepala-gudang/permintaan-stok/{id}/reject', [RequestStokController::class, 'reject'])->name('kepala.permintaan-stok.reject');
        Route::delete('/kepala-gudang/permintaan-stok/{id}', [RequestStokController::class, 'destroy'])->name('kepala.permintaan-stok.destroy');
    });
});
