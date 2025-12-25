<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\Stok;
use App\Models\DetailPenjualan;
use App\Models\ClosingHarian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProdukRaiderController extends Controller
{
    public function index()
    {
        $role = strtolower(trim(session('user.role') ?? ''));
        if (!in_array($role, ['kepala_gudang', 'raider'])) {
            abort(403);
        }

        // List all Cabang (Branches)
        $cabangs = Cabang::all();
        
        return view('kepala-gudang.produk-raider.index', compact('cabangs'));
    }

    public function show(Request $request, $cabangId)
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'raider'])) {
            abort(403);
        }

        $cabang = Cabang::findOrFail($cabangId);

        // Show CURRENT STOCK for this branch
        $stokCabang = Stok::with('produk')
            ->where('cabang_id', $cabangId)
            ->where('jumlah', '>', 0)
            ->get();

        // Filter visibility by 1 day (if set)
        $stokCabang = $stokCabang->filter(function($s) {
                $key = "cabang:{$s->cabang_id}:stok_visible:{$s->produk_id}";
                return Cache::get($key, false);
            });

        // Compute today's sold quantity per product in this branch
        $soldToday = DetailPenjualan::whereHas('penjualan', function ($q) use ($cabangId) {
                $q->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
                  ->where('cabang_id', $cabangId);
            })
            ->select('produk_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('produk_id')
            ->pluck('total', 'produk_id');

        // Attach remaining today to each stock item
        $stokCabang->each(function ($item) use ($soldToday) {
            $sold = (int) ($soldToday[$item->produk_id] ?? 0);
            $item->sisa_hari_ini = max((int)$item->jumlah - $sold, 0);
        });

        // Build info banner data from Raider's input (ClosingHarian)
        $unsoldProducts = collect();
        $closing = ClosingHarian::where('cabang_id', $cabangId)
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
            ->orderBy('created_at', 'desc')
            ->first();
        if ($closing && $closing->stok_akhir) {
            $payload = json_decode($closing->stok_akhir, true) ?: [];
            $detail = $payload['detail'] ?? [];
            $stokMap = Stok::where('cabang_id', $cabangId)->get()->keyBy('produk_id');
            foreach ($detail as $row) {
                $pid = (int) ($row['produk_id'] ?? 0);
                $sisa = (int) ($row['sisa'] ?? 0);
                $awal = (int) ($stokMap[$pid]->jumlah ?? 0);
                if ($awal > 0 && $sisa === $awal) {
                    $produk = Produk::find($pid);
                    if ($produk && $produk->nama_produk) {
                        $unsoldProducts->push($produk->nama_produk);
                    }
                }
            }
        }

        if ($request->ajax()) {
            return view('kepala-gudang.produk-raider.partials.product-list', compact('cabang', 'stokCabang', 'unsoldProducts'));
        }

        return view('kepala-gudang.produk-raider.show', compact('cabang', 'stokCabang', 'unsoldProducts'));
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

                // Set visibility cache for 1 day
                $key = "cabang:{$cabangId}:stok_visible:{$produkId}";
                Cache::put($key, true, now()->addDay());
            }

            DB::commit();
            return redirect()->route('kepala.produk-raider.show', $cabangId)->with('success', 'Stok berhasil ditambahkan ke Cabang');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan stok: ' . $e->getMessage());
        }
    }
}
