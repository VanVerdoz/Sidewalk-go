<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    // GET semua cabang
    public function index()
    {
        return $this->daftar();
    }

    // POST tambah cabang
    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    // GET cabang by id
    public function show($id)
    {
        return $this->detail($id);
    }

    // PUT update cabang
    public function update(Request $request, $id)
    {
        return $this->perbarui($request, $id);
    }

    // DELETE cabang
    public function destroy($id)
    {
        return $this->hapus($id);
    }

    public function daftar()
    {
        return response()->json([
            'message' => 'Daftar cabang',
            'data' => Cabang::orderBy('id', 'asc')->get()
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:100',
            'alamat' => 'required|string',
            'status' => 'nullable|string'
        ]);

        $cabang = Cabang::create($request->only([
            'nama_cabang',
            'alamat',
            'status'
        ]));

        return response()->json([
            'message' => 'Cabang berhasil ditambahkan',
            'data' => $cabang
        ], 201);
    }

    public function detail($id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail cabang',
            'data' => $cabang
        ]);
    }

    public function perbarui(Request $request, $id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_cabang' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        $cabang->update($request->only([
            'nama_cabang',
            'alamat',
            'status'
        ]));

        return response()->json([
            'message' => 'Cabang berhasil diperbarui',
            'data' => $cabang
        ]);
    }

    public function hapus($id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        }

        $cabang->delete();

        return response()->json(['message' => 'Cabang berhasil dihapus']);
    }
}
