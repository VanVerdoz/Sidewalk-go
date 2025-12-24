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

        // Show history of requests/transfers for this branch
        $transfers = RequestStok::with(['details.produk'])
            ->where('cabang_id', $cabangId)
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('kepala-gudang.produk-raider.show', compact('cabang', 'transfers'));
    }

    public function create(Request $request, $cabangId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $cabang = Cabang::findOrFail($cabangId);
        
        // Ambil semua produk
        // Mengambil stok dari Cabang Utama (first branch created or by ID pattern if needed)
        // Asumsi: Gudang Utama adalah Cabang pertama yang dibuat atau yang memiliki stok terbanyak
        // Kita akan ambil total stok dari seluruh sistem saja sebagai referensi, atau stok gudang utama
        
        // Ambil Gudang Utama (Asumsi Cabang dengan ID terkecil atau pattern tertentu)
        // Di sini kita ambil total stok dari tabel stok untuk produk tersebut (sum)
        // Atau ambil stok dari cabang "Pusat" jika ada. 
        // Untuk amannya, kita tampilkan total stok yang tersedia di sistem.
        
        $stokCabang = Produk::with(['stok' => function($query) {
            // Jika ingin stok gudang utama saja, filter by cabang_id tertentu
            // Tapi karena user bilang "gausah di ambil di stok produk" (deduct), 
            // kita hanya tampilkan info saja.
        }])->get();
        
        // Map total stok
        $stokCabang->each(function($product) {
            // Hitung total stok yang ada di tabel stok untuk produk ini
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

            // Create RequestStok for the specific Cabang
            $req = RequestStok::create([
                'cabang_id' => (int) $cabangId,
                'raider_id' => null, // No specific raider, just branch
                'status' => 'disetujui',
                'catatan' => 'Dikirim oleh Kepala Gudang',
                'tanggal' => now('Asia/Jakarta'),
            ]);

            foreach ($request->produk_id as $index => $produkId) {
                $jumlah = (int) $request->jumlah[$index];
                if (!$produkId || !$jumlah) continue;

                // Create RequestStokDetail
                RequestStokDetail::create([
                    'request_id' => $req->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                ]);

                // Note: Stock deduction from Gudang is disabled per user request
            }

            DB::commit();
            return redirect()->route('kepala.produk-raider.show', $cabangId)->with('success', 'Stok berhasil dikirim ke Cabang');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim stok: ' . $e->getMessage());
        }
    }
}
