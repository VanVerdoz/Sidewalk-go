<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Cabang;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProdukController extends Controller
{
    public function index()
    {
        return $this->daftar();
    }

    public function create()
    {
        return $this->buat();
    }

    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    public function show(string $id)
    {
        return $this->detail($id);
    }

    public function edit(string $id)
    {
        return $this->ubah($id);
    }

    public function update(Request $request, string $id)
    {
        return $this->perbarui($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->hapus($id);
    }

    public function daftar()
    {
        $produk = Produk::with('stok')->get();
        return view('produk.index', compact('produk'));
    }

    public function buat()
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'owner'])) {
            abort(403);
        }
        $cabang = Cabang::all();
        return view('produk.create', compact('cabang'));
    }

    public function simpan(Request $request)
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'owner'])) {
            abort(403);
        }
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::create([
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'kategori' => $request->kategori,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
            ]);

            $cabangUtama = Cabang::first();
            if ($cabangUtama && $request->jumlah > 0) {
                Stok::create([
                    'produk_id' => $produk->id,
                    'cabang_id' => $cabangUtama->id,
                    'jumlah' => $request->jumlah,
                ]);
                // Set visibility cache for 1 day
                $key = "cabang:{$cabangUtama->id}:stok_visible:{$produk->id}";
                Cache::put($key, true, now()->addDay());
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function detail(string $id)
    {
        $produk = Produk::with('stok')->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function ubah(string $id)
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'owner'])) {
            abort(403);
        }
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function perbarui(Request $request, string $id)
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'owner'])) {
            abort(403);
        }
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'tambah_stok' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::findOrFail($id);
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'kategori' => $request->kategori,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
            ]);

            if ($request->filled('tambah_stok') && $request->tambah_stok > 0) {
                // Ambil cabang pertama sebagai "Stok Induk"
                $cabangUtama = Cabang::first();
                
                if ($cabangUtama) {
                    $stok = Stok::firstOrNew([
                        'produk_id' => $produk->id,
                        'cabang_id' => $cabangUtama->id,
                    ]);
                    $stok->jumlah = ($stok->exists ? $stok->jumlah : 0) + $request->tambah_stok;
                    $stok->save();
                }
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal update produk: ' . $e->getMessage());
        }
    }

    public function hapus(string $id)
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'owner'])) {
            abort(403);
        }
        $produk = Produk::findOrFail($id);

        try {
            DB::beginTransaction();

            // Hapus stok terkait
            $produk->stok()->delete();

            // Hapus detail penjualan terkait (PERINGATAN: Ini menghapus histori item transaksi)
            $produk->detailPenjualan()->delete();

            $produk->delete();
            
            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
