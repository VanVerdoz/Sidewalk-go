<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\LaporanKeuangan;
use App\Models\RequestStok;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class PenggunaController extends Controller
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
        $pengguna = Pengguna::all();
        return view('pengguna.index', compact('pengguna'));
    }

    public function buat()
    {
        if (session('user.role') === 'owner') {
            abort(403);
        }
        return view('pengguna.create');
    }

    public function simpan(Request $request)
    {
        if (session('user.role') === 'owner') {
            abort(403);
        }
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|unique:pengguna,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:owner,admin,kepala_gudang,raider',
            'status' => 'nullable|in:active,inactive',
        ]);

        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function detail(string $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.show', compact('pengguna'));
    }

    public function ubah(string $id)
    {
        if (session('user.role') === 'owner') {
            abort(403);
        }
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    public function perbarui(Request $request, string $id)
    {
        if (session('user.role') === 'owner') {
            abort(403);
        }
        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|unique:pengguna,username,' . $id,
            'role' => 'required|in:owner,admin,kepala_gudang,raider',
            'status' => 'nullable|in:active,inactive',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'role' => $request->role,
            'status' => $request->status ?? 'active',
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diupdate');
    }

    public function hapus(string $id)
    {
        if (session('user.role') !== 'admin') {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // 1. Set null pada Laporan Keuangan (karena FK constraint on delete no action)
            LaporanKeuangan::where('dibuat_oleh', $id)->update(['dibuat_oleh' => null]);

            // 2. Set null pada Request Stok (jika ada relasi raider)
            RequestStok::where('raider_id', $id)->update(['raider_id' => null]);

            // 3. Set null pada Penjualan (jika belum otomatis set null)
            Penjualan::where('pengguna_id', $id)->update(['pengguna_id' => null]);

            $pengguna = Pengguna::findOrFail($id);
            $pengguna->delete();

            DB::commit();
            return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
