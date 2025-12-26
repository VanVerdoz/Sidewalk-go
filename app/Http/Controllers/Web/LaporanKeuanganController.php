<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKeuangan;
use App\Models\Cabang;

class LaporanKeuanganController extends Controller
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
        $laporan = LaporanKeuangan::with('cabang')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('laporan-keuangan.index', compact('laporan'));
    }

    public function buat()
    {
        $cabang = Cabang::orderBy('id', 'asc')->get();
        return view('laporan-keuangan.create', compact('cabang'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
            'total_pendapatan' => 'required|numeric',
        ]);

        LaporanKeuangan::create([
            'cabang_id' => $request->cabang_id,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'total_pendapatan' => $request->total_pendapatan,
            'dibuat_oleh' => session('user.id'),
        ]);

        return redirect()->route('laporan-keuangan.index')->with('success', 'Laporan keuangan berhasil ditambahkan');
    }

    public function detail(string $id)
    {
        $laporan = LaporanKeuangan::with('cabang')->findOrFail($id);
        return view('laporan-keuangan.show', compact('laporan'));
    }

    public function ubah(string $id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);
        $cabang = Cabang::orderBy('id', 'asc')->get();
        return view('laporan-keuangan.edit', compact('laporan', 'cabang'));
    }

    public function perbarui(Request $request, string $id)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
            'total_pendapatan' => 'required|numeric',
        ]);

        $laporan = LaporanKeuangan::findOrFail($id);
        $laporan->update([
            'cabang_id' => $request->cabang_id,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'total_pendapatan' => $request->total_pendapatan,
        ]);

        return redirect()->route('laporan-keuangan.index')->with('success', 'Laporan keuangan berhasil diupdate');
    }

    public function hapus(string $id)
    {
        if (!in_array(session('user.role'), ['admin', 'owner'])) {
            abort(403);
        }
        $laporan = LaporanKeuangan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('laporan-keuangan.index')->with('success', 'Laporan keuangan berhasil dihapus');
    }
}
