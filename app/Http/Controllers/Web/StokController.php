<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\Cabang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->daftar();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->buat();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->detail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->ubah($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->perbarui($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->hapus($id);
    }

    public function daftar()
    {
        $query = Stok::with(['cabang', 'produk']);

        // Filter berdasarkan role jika perlu (contoh: kepala cabang hanya lihat cabangnya)
        // Saat ini tampilkan semua
        
        $stok = $query->orderBy('id', 'desc')->get();
        return view('stok.index', compact('stok'));
    }

    public function buat()
    {
        $cabang = Cabang::all();
        $produk = Produk::all();
        return view('stok.create', compact('cabang', 'produk'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|numeric|min:0',
        ]);

        // Cek apakah stok untuk produk ini di cabang ini sudah ada
        $existingStok = Stok::where('cabang_id', $request->cabang_id)
                            ->where('produk_id', $request->produk_id)
                            ->first();

        if ($existingStok) {
            return redirect()->back()->with('error', 'Stok untuk produk ini di cabang tersebut sudah ada. Silakan edit data yang sudah ada.');
        }

        Stok::create([
            'cabang_id' => $request->cabang_id,
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah
        ]);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan');
    }

    public function detail(string $id)
    {
        // Biasanya tidak butuh view detail khusus untuk stok, tapi jika ada:
        // return view('stok.show', compact('stok'));
        return redirect()->route('stok.index');
    }

    public function ubah(string $id)
    {
        $stok = Stok::findOrFail($id);
        $cabang = Cabang::all();
        $produk = Produk::all();
        return view('stok.edit', compact('stok', 'cabang', 'produk'));
    }

    public function perbarui(Request $request, string $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ]);

        $stok = Stok::findOrFail($id);
        $stok->update([
            'jumlah' => $request->jumlah
        ]);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil diperbarui');
    }

    public function hapus(string $id)
    {
        $stok = Stok::findOrFail($id);
        try {
            $stok->delete();
            return redirect()->route('stok.index')->with('success', 'Stok berhasil dihapus');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Stok tidak dapat dihapus karena sedang digunakan dalam transaksi.');
        }
    }
}
