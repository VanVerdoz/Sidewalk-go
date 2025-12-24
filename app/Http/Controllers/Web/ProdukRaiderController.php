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
        
        // Ambil semua produk (tanpa filter stok cabang)
        $stokCabang = Produk::all();
        
        // Tambahkan properti dummy untuk kompatibilitas view
        $stokCabang->each(function($product) {
            $product->stok_jumlah = 9999; // Unlimited/Not tracked
            $product->total_stok = 9999;
        });

        // Tidak ada cabang terpilih
        $selectedCabang = null;
        $cabangList = [];

        return view('kepala-gudang.produk-raider.create', compact('raider', 'stokCabang', 'selectedCabang', 'cabangList'));
    }

    public function store(Request $request, $raiderId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $request->validate([
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Gunakan Gudang Utama sebagai default jika ada, jika tidak null
            $cabangUtama = Cabang::first();
            $cabangId = $cabangUtama ? $cabangUtama->id : null;
            
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

                // 3. SKIP Stock Deduction from Cabang/Gudang
                // Sesuai request user: "gausah di ambil di stok produk"
            }

            DB::commit();
            return redirect()->route('kepala.produk-raider.show', $raiderId)->with('success', 'Stok berhasil dikirim ke Raider');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim stok: ' . $e->getMessage());
        }
    }
}
