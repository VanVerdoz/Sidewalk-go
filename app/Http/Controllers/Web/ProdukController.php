<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Stok;

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
        return view('produk.create');
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
        ]);

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
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
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function hapus(string $id)
    {
        if (!in_array(session('user.role'), ['kepala_gudang', 'owner'])) {
            abort(403);
        }
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
