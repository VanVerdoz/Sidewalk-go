<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\RequestStok;
use App\Models\RequestStokDetail;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;

class ProdukRaiderController extends Controller
{
    public function index()
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        // List all Raiders
        $raiders = Pengguna::where('role', 'raider')->get();

        // Attach inferred Cabang to each raider based on last stock request
        foreach ($raiders as $raider) {
            $lastRequest = RequestStok::where('raider_id', $raider->id)
                ->with('cabang')
                ->orderBy('tanggal', 'desc')
                ->first();
            
            $raider->cabang_name = $lastRequest && $lastRequest->cabang 
                ? $lastRequest->cabang->nama_cabang 
                : '-';
        }
        
        return view('kepala-gudang.produk-raider.index', compact('raiders'));
    }

    public function show($raiderId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $raider = Pengguna::findOrFail($raiderId);

        // Show history of requests/transfers
        $transfers = RequestStok::with(['details.produk', 'cabang'])
            ->where('raider_id', $raiderId)
            ->where('status', 'disetujui')
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('kepala-gudang.produk-raider.show', compact('raider', 'transfers'));
    }

    public function create(Request $request, $raiderId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $raider = Pengguna::findOrFail($raiderId);
        
        // Use first Cabang (Main Warehouse) as source
        $cabangUtama = Cabang::first();
        if (!$cabangUtama) {
            return back()->with('error', 'Tidak ada data cabang/gudang ditemukan.');
        }

        $selectedCabang = $cabangUtama;
        $selectedCabangId = $cabangUtama->id;

        // Ambil semua produk dengan stok di Gudang Utama
        $stokCabang = Produk::with('stok')->get();

        // Map stok_jumlah agar mudah diakses di view
        $stokCabang->each(function($product) use ($selectedCabangId) {
            $stokDiCabang = $product->stok->where('cabang_id', $selectedCabangId)->first();
            // Use Total Stock as the available stock since we are sending from Main Warehouse which aggregates it?
            // Actually, if we just added stock to Cabang::first(), then $stokDiCabang->jumlah IS the correct amount.
            // But user saw 0 in SW-02.
            // If Cabang::first() is SW-01 (Gudang Utama), then we should show SW-01 stock.
            $product->stok_jumlah = $stokDiCabang ? $stokDiCabang->jumlah : 0;
            $product->total_stok = $product->stok->sum('jumlah');
        });

        return view('kepala-gudang.produk-raider.create', compact('raider', 'stokCabang', 'selectedCabang'));
    }

    public function store(Request $request, $raiderId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $request->validate([
            // 'cabang_id' => 'required|exists:cabang,id', // Removed validation requirement from request
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Always use Main Warehouse
            $cabangUtama = Cabang::first();
            if (!$cabangUtama) {
                throw new \Exception('Gudang Utama tidak ditemukan.');
            }
            $cabangId = $cabangUtama->id;
            
            // 1. Create RequestStok (Approved)
            $req = RequestStok::create([
                'cabang_id' => $cabangId,
                'raider_id' => (int) $raiderId,
                'status' => 'disetujui',
                'catatan' => 'Dikirim oleh Kepala Gudang | ' . ($request->catatan ?? '-'),
                'tanggal' => now('Asia/Jakarta'),
            ]);

            foreach ($request->produk_id as $index => $produkId) {
                $jumlah = (int) $request->jumlah[$index];
                if (!$produkId || !$jumlah) continue;

                // 2. Create RequestStokDetail
                RequestStokDetail::create([
                    'request_id' => $req->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                ]);

                // 3. Deduct from Cabang Stock
                $stok = Stok::where('cabang_id', $cabangId)
                            ->where('produk_id', $produkId)
                            ->lockForUpdate()
                            ->first();

                if (!$stok || $stok->jumlah < $jumlah) {
                    throw new \Exception('Stok tidak mencukupi untuk produk ID: ' . $produkId);
                }

                $stok->jumlah -= $jumlah;
                $stok->save();
            }

            DB::commit();
            return redirect()->route('kepala.produk-raider.show', $raiderId)->with('success', 'Stok berhasil dikirim ke Raider');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim stok: ' . $e->getMessage());
        }
    }
}
