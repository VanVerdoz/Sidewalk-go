<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // === CREATE USER (hanya ADMIN) ===
    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    // === LIST USER (ADMIN & OWNER) ===
    public function index(Request $request)
    {
        return $this->daftar($request);
    }

    // === UPDATE USER (hanya ADMIN) ===
    public function update(Request $request, $id)
    {
        return $this->perbarui($request, $id);
    }

    // === DELETE USER (hanya ADMIN) ===
    public function destroy(Request $request, $id)
    {
        return $this->hapus($request, $id);
    }

    // === UBAH STATUS SAJA (ACTIVE / INACTIVE) ===
    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $pengguna = Pengguna::findOrFail($id);
        $pengguna->status = $request->status;
        $pengguna->save();

        return response()->json([
            'message' => 'Status pengguna diperbarui',
            'data' => $pengguna
        ]);
    }

    public function simpan(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya admin yang boleh membuat pengguna.'
            ], 403);
        }

        $request->validate([
            'username' => 'required|unique:pengguna',
            'password' => 'required|min:6',
            'nama_lengkap' => 'required',
            'role' => 'required',
            'status' => 'in:active,inactive'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['status'] = $request->status ?? 'active';

        $pengguna = Pengguna::create($data);

        return response()->json([
            'message' => 'Pengguna berhasil dibuat',
            'data' => $pengguna
        ], 201);
    }

    public function daftar(Request $request)
    {
        if (!in_array($request->user()->role, ['admin', 'owner'])) {
            return response()->json([
                'message' => 'Akses ditolak. Hanya admin dan owner yang boleh melihat data pengguna.'
            ], 403);
        }

        return response()->json([
            'message' => 'Daftar pengguna',
            'data' => Pengguna::all()
        ]);
    }

    public function perbarui(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya admin yang boleh memperbarui pengguna.'
            ], 403);
        }

        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            'status' => 'in:active,inactive'
        ]);

        if ($request->password) {
            $request['password'] = Hash::make($request->password);
        }

        $pengguna->update($request->all());

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui',
            'data' => $pengguna
        ]);
    }

    public function hapus(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya admin yang boleh menghapus pengguna.'
            ], 403);
        }

        Pengguna::destroy($id);

        return response()->json(['message' => 'Pengguna dihapus']);
    }
}
