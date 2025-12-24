<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;

class ProdukRaiderController extends Controller
{
    public function index()
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        // List all Cabang (Branches)
        $cabangs = Cabang::all();
        
        return view('kepala-gudang.produk-raider.index', compact('cabangs'));
    }

    public function show($cabangId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $cabang = Cabang::findOrFail($cabangId);

        // Show CURRENT STOCK for this branch
        $stokCabang = Stok::with('produk')
            ->where('cabang_id', $cabangId)
            ->where('jumlah', '>', 0)
            ->get();

        return view('kepala-gudang.produk-raider.show', compact('cabang', 'stokCabang'));
    }

    public function create(Request $request, $cabangId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $cabang = Cabang::findOrFail($cabangId);
        
        // Ambil semua produk dan hitung total stok di sistem
        $stokCabang = Produk::with(['stok'])->get();
        
        $stokCabang->each(function($product) {
            $totalStok = $product->stok->sum('jumlah');
            $product->stok_jumlah = $totalStok; 
            $product->total_stok = $totalStok;
        });

        return view('kepala-gudang.produk-raider.create', compact('cabang', 'stokCabang'));
    }

    public function store(Request $request, $cabangId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $request->validate([
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->produk_id as $index => $produkId) {
                $jumlah = (int) $request->jumlah[$index];
                if (!$produkId || !$jumlah) continue;

                // Update or Create Stok entry for this Cabang
                $stok = Stok::firstOrNew([
                    'cabang_id' => $cabangId,
                    'produk_id' => $produkId
                ]);

                $stok->jumlah = ($stok->exists ? $stok->jumlah : 0) + $jumlah;
                $stok->save();
            }

            DB::commit();
            return redirect()->route('kepala.produk-raider.show', $cabangId)->with('success', 'Stok berhasil ditambahkan ke Cabang');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }
}
