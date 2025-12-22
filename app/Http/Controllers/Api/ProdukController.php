<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // === LIST PRODUK ===
    public function index()
    {
        return $this->daftar();
    }

    // === DETAIL PRODUK ===
    public function show($id)
    {
        return $this->detail($id);
    }

    // === CREATE PRODUK (kepala_gudang saja) ===
    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    // === UPDATE PRODUK ===
    public function update(Request $request, $id)
    {
        return $this->perbarui($request, $id);
    }

    // === DELETE PRODUK ===
    public function destroy($id)
    {
        return $this->hapus($id);
    }

    // === UPDATE STATUS SAJA ===
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $produk = Produk::findOrFail($id);
        $produk->status = $request->status;
        $produk->save();

        return response()->json([
            'message' => 'Status produk diperbarui',
            'data' => $produk
        ]);
    }

    public function daftar()
    {
        $produk = Produk::all()->map(function ($item) {
            $item->harga_format = 'Rp. ' . number_format($item->harga, 0, ',', '.');
            return $item;
        });

        return response()->json([
            'message' => 'Daftar produk',
            'data' => $produk
        ]);
    }

    public function detail($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->harga_format = 'Rp. ' . number_format($produk->harga, 0, ',', '.');

        return response()->json([
            'message' => 'Detail produk',
            'data' => $produk
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|integer',
            'kategori' => 'required',
            'status' => 'in:active,inactive',
            'deskripsi' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['status'] = $request->status ?? 'active';

        $produk = Produk::create($data);
        $produk->harga_format = 'Rp. ' . number_format($produk->harga, 0, ',', '.');

        return response()->json([
            'message' => 'Produk berhasil dibuat',
            'data' => $produk
        ], 201);
    }

    public function perbarui(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'status' => 'in:active,inactive'
        ]);

        $produk->update($request->all());
        $produk->harga_format = 'Rp. ' . number_format($produk->harga, 0, ',', '.');

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => $produk
        ]);
    }

    public function hapus($id)
    {
        Produk::destroy($id);

        return response()->json(['message' => 'Produk dihapus']);
    }
}
