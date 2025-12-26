<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cabang;

class CabangController extends Controller
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
        $cabang = Cabang::orderBy('id', 'asc')->get();
        return view('cabang.index', compact('cabang'));
    }

    public function buat()
    {
        if (session('user.role') === 'owner') {
            abort(403);
        }
        return view('cabang.create');
    }

    public function simpan(Request $request)
    {
        if (session('user.role') === 'owner') {
            abort(403);
        }
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'status' => 'nullable|string',
        ]);

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'status' => $request->status ?? 'aktif',
        ]);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan');
    }

    public function detail(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        return view('cabang.show', compact('cabang'));
    }

    public function ubah(string $id)
    {
        $cabang = Cabang::findOrFail($id);
        return view('cabang.edit', compact('cabang'));
    }

    public function perbarui(Request $request, string $id)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'status' => 'nullable|string',
        ]);

        $cabang = Cabang::findOrFail($id);
        $cabang->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'status' => $request->status ?? 'aktif',
        ]);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diupdate');
    }

    public function hapus(string $id)
    {
        if (session('user.role') !== 'admin') {
            abort(403);
        }
        $cabang = Cabang::findOrFail($id);
        $cabang->delete();

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus');
    }
}
