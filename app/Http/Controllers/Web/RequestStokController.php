<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\Pengguna;
use App\Models\RequestStok;
use App\Models\RequestStokDetail;

class RequestStokController extends Controller
{
    public function index()
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $cabangList = RequestStok::select('id_cabang')
            ->groupBy('id_cabang')
            ->get()
            ->map(function ($row) {
                $cabang = Cabang::find($row->id_cabang);
                $pendingCount = RequestStok::where('id_cabang', (int) $row->id_cabang)
                    ->where('status_permintaan', 'pending')
                    ->count();
                $riderCount = RequestStok::where('id_cabang', (int) $row->id_cabang)
                    ->select('id_raider')->groupBy('id_raider')->count();
                return [
                    'cabang' => $cabang,
                    'pending' => $pendingCount,
                    'riders' => $riderCount,
                ];
            });

        return view('kepala-gudang.permintaan-stok', compact('cabangList'));
    }

    public function cabangView($cabangId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $cabang = \App\Models\Cabang::findOrFail($cabangId);
        $permintaan = RequestStok::with(['raider', 'details.produk'])
            ->where('id_cabang', (int) $cabangId)
            ->orderBy('dibuat_pada', 'desc')
            ->paginate(20);

        return view('kepala-gudang.permintaan.cabang', compact('cabang', 'permintaan'));
    }

    public function riderView($cabangId, $raiderId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $cabang = \App\Models\Cabang::findOrFail($cabangId);
        $raider = \App\Models\Pengguna::findOrFail($raiderId);

        $permintaan = RequestStok::with(['details.produk'])
            ->where('id_cabang', (int) $cabangId)
            ->where('id_raider', (int) $raiderId)
            ->orderBy('dibuat_pada', 'desc')
            ->get();

        return view('kepala-gudang.permintaan.rider', compact('cabang', 'raider', 'permintaan'));
    }

    public function detailView($permintaanId)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::with(['details.produk', 'cabang', 'raider'])->where('id_permintaan', $permintaanId)->firstOrFail();

        return view('kepala-gudang.permintaan.detail', compact('req'));
    }
    public function create()
    {
        if (session('user.role') !== 'raider') {
            abort(403);
        }

        $produk = Produk::all();
        $cabang = Cabang::all();
        $userIdRaw    = session('user.id');
        $userUsername = session('user.username') ?? null;
        $penggunaId = null;
        if (!is_null($userIdRaw) && is_numeric($userIdRaw)) {
            $penggunaId = (int) $userIdRaw;
        } else {
            if ($userUsername) {
                $pengguna = Pengguna::where('username', $userUsername)->first();
                if ($pengguna && is_numeric($pengguna->id)) {
                    $penggunaId = (int) $pengguna->id;
                }
            }
        }

        $riwayat = collect();
        if (!is_null($penggunaId)) {
            $today = now('Asia/Jakarta')->toDateString();
            $riwayat = RequestStok::with(['details.produk', 'cabang'])
                ->where('id_raider', $penggunaId)
                ->whereDate('dibuat_pada', $today)
                ->orderBy('dibuat_pada', 'desc')
                ->paginate(10);
        }

        return view('raider.permintaan-stok', compact('produk', 'cabang', 'riwayat'));
    }

    public function store(Request $request)
    {
        if (session('user.role') !== 'raider') {
            abort(403);
        }

        $request->validate([
            'cabang_id'   => 'required|exists:cabang,id',
            'produk_id'   => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'jumlah'      => 'required|array|min:1',
            'jumlah.*'    => 'required|integer|min:1',
            'catatan'     => 'nullable|string',
        ]);

        $userIdRaw    = session('user.id');
        $userNama     = session('user.nama_lengkap') ?? null;
        $userUsername = session('user.username') ?? null;
        $displayNama  = $userNama ?: ($userUsername ?: $userIdRaw);

        $catatan = trim((string) $request->catatan);
        $note = 'Permintaan stok oleh: ' . $displayNama;
        if ($catatan !== '') {
            $note .= ' | Catatan: ' . $catatan;
        }

        $penggunaId = null;
        if (!is_null($userIdRaw) && is_numeric($userIdRaw)) {
            $penggunaId = (int) $userIdRaw;
        } else {
            if ($userUsername) {
                $pengguna = Pengguna::where('username', $userUsername)->first();
                if ($pengguna && is_numeric($pengguna->id)) {
                    $penggunaId = (int) $pengguna->id;
                }
            }
        }

        if (is_null($penggunaId)) {
            return back()->withErrors([
                'raider_id' => 'ID pengguna tidak valid untuk membuat permintaan stok.',
            ])->withInput();
        }

        $idPermintaan = 'RS-' . now('Asia/Jakarta')->format('YmdHis') . '-' . $penggunaId;

        $req = RequestStok::create([
            'id_permintaan'     => $idPermintaan,
            'id_cabang'         => (int) $request->cabang_id,
            'id_raider'         => $penggunaId,
            'status_permintaan' => 'pending',
            'keterangan'        => $note,
            'dibuat_pada'       => now('Asia/Jakarta'),
            'diperbarui_pada'   => now('Asia/Jakarta'),
        ]);

        foreach ($request->produk_id as $index => $produkId) {
            $jumlah = $request->jumlah[$index] ?? null;
            if (!$produkId || !$jumlah) {
                continue;
            }

            $idDetail = 'RSD-' . now('Asia/Jakarta')->format('YmdHis') . '-' . $index;

            RequestStokDetail::create([
                'id_detail'     => $idDetail,
                'id_permintaan' => $req->id_permintaan,
                'id_produk'     => (int) $produkId,
                'jumlah'        => (int) $jumlah,
            ]);
        }

        return redirect()->route('raider.permintaan-stok.create')->with('success', 'Permintaan stok berhasil dibuat');
    }

    public function approve($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        $note = trim((string) $req->keterangan);
        if ($note !== '') {
            $note .= ' | Disetujui Kepala Gudang pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        } else {
            $note = 'Disetujui Kepala Gudang pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        }

        $req->keterangan = $note;
        $req->status_permintaan = 'disetujui';
        $req->disetujui_oleh = (int) (session('user.id'));
        $req->waktu_disetujui = now('Asia/Jakarta');
        $req->save();

        return back()->with('success', 'Permintaan stok disetujui');
    }

    public function pending($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        $note = trim((string) $req->keterangan);
        if ($note !== '') {
            $note .= ' | Status diubah ke pending pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        } else {
            $note = 'Status diubah ke pending pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        }

        $req->keterangan = $note;
        $req->status_permintaan = 'pending';
        $req->disetujui_oleh = null;
        $req->waktu_disetujui = null;
        $req->save();

        return back()->with('success', 'Permintaan stok dikembalikan ke status pending');
    }

    public function destroy($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        RequestStokDetail::where('id_permintaan', $req->id_permintaan)->delete();
        $req->delete();

        return back()->with('success', 'Permintaan stok dihapus');
    }

    public function reject($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        $note = trim((string) $req->keterangan);
        if ($note !== '') {
            $note .= ' | Ditolak pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        } else {
            $note = 'Ditolak pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        }

        $req->keterangan = $note;
        $req->status_permintaan = 'ditolak';
        $req->disetujui_oleh = null;
        $req->waktu_disetujui = null;
        $req->save();

        return back()->with('success', 'Permintaan stok ditolak');
    }
}
