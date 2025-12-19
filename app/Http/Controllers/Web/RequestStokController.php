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
        try {
            if (session('user.role') !== 'kepala_gudang') {
                abort(403);
            }

            $cabangList = RequestStok::select('cabang_id')
                ->groupBy('cabang_id')
                ->get()
                ->map(function ($row) {
                    $cabang = Cabang::find($row->cabang_id);
                    $pendingCount = RequestStok::where('cabang_id', (int) $row->cabang_id)
                        ->where('status', 'pending')
                        ->count();
                    $riderCount = RequestStok::where('cabang_id', (int) $row->cabang_id)
                        ->select('raider_id')->groupBy('raider_id')->count();
                    return [
                        'cabang' => $cabang,
                        'pending' => $pendingCount,
                        'riders' => $riderCount,
                    ];
                });

            return response(view('kepala-gudang.permintaan-stok', compact('cabangList'))->render());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Index Error: ' . $e->getMessage());
            return response("<h1>ERROR CAUGHT IN Index</h1><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>", 200);
        }
    }

    public function cabangView($cabangId)
    {
        try {
            if (session('user.role') !== 'kepala_gudang') {
                abort(403);
            }

            $cabang = \App\Models\Cabang::findOrFail($cabangId);
            $permintaan = RequestStok::with(['raider', 'details.produk'])
                ->where('cabang_id', (int) $cabangId)
                ->orderBy('tanggal', 'desc')
                ->paginate(20);

            return response(view('kepala-gudang.permintaan.cabang', compact('cabang', 'permintaan'))->render());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('CabangView Error: ' . $e->getMessage());
            return response("<h1>ERROR CAUGHT IN CabangView</h1><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>", 200);
        }
    }

    public function riderView($cabangId, $raiderId)
    {
        try {
            if (session('user.role') !== 'kepala_gudang') {
                abort(403);
            }

            $cabang = \App\Models\Cabang::findOrFail($cabangId);
            $raider = \App\Models\Pengguna::findOrFail($raiderId);

            $permintaan = RequestStok::with(['details.produk'])
                ->where('cabang_id', (int) $cabangId)
                ->where('raider_id', (int) $raiderId)
                ->orderBy('tanggal', 'desc')
                ->get();

            return response(view('kepala-gudang.permintaan.rider', compact('cabang', 'raider', 'permintaan'))->render());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RiderView Error: ' . $e->getMessage());
            return response("<h1>ERROR CAUGHT IN RiderView</h1><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>", 200);
        }
    }

    public function detailView($permintaanId)
    {
        try {
            if (session('user.role') !== 'kepala_gudang') {
                abort(403);
            }

            // PK is 'id', not 'id_permintaan'
            $req = RequestStok::with(['details.produk', 'cabang', 'raider'])->where('id', $permintaanId)->firstOrFail();

            return response(view('kepala-gudang.permintaan.detail', compact('req'))->render());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('DetailView Error: ' . $e->getMessage());
            return response("<h1>ERROR CAUGHT IN DetailView</h1><p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString() . "</pre>", 200);
        }
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
                ->where('raider_id', $penggunaId)
                ->whereDate('tanggal', $today)
                ->orderBy('tanggal', 'desc')
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

        $req = RequestStok::create([
            'cabang_id'         => (int) $request->cabang_id,
            'raider_id'         => $penggunaId,
            'status'            => 'pending',
            'catatan'           => $note,
            'tanggal'           => now('Asia/Jakarta'),
        ]);

        foreach ($request->produk_id as $index => $produkId) {
            $jumlah = $request->jumlah[$index] ?? null;
            if (!$produkId || !$jumlah) {
                continue;
            }

            RequestStokDetail::create([
                'request_id'    => $req->id,
                'produk_id'     => (int) $produkId,
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
        $note = trim((string) $req->catatan);
        if ($note !== '') {
            $note .= ' | Disetujui Kepala Gudang pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        } else {
            $note = 'Disetujui Kepala Gudang pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        }

        $req->catatan = $note;
        $req->status = 'disetujui';
        // $req->disetujui_oleh = (int) (session('user.id')); // Column not exists
        // $req->waktu_disetujui = now('Asia/Jakarta'); // Column not exists
        $req->save();

        return back()->with('success', 'Permintaan stok disetujui');
    }

    public function pending($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        $note = trim((string) $req->catatan);
        if ($note !== '') {
            $note .= ' | Status diubah ke pending pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        } else {
            $note = 'Status diubah ke pending pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        }

        $req->catatan = $note;
        $req->status = 'pending';
        // $req->disetujui_oleh = null;
        // $req->waktu_disetujui = null;
        $req->save();

        return back()->with('success', 'Permintaan stok dikembalikan ke status pending');
    }

    public function destroy($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        // PK is id, details fk is request_id
        RequestStokDetail::where('request_id', $req->id)->delete();
        $req->delete();

        return back()->with('success', 'Permintaan stok dihapus');
    }

    public function reject($id)
    {
        if (session('user.role') !== 'kepala_gudang') {
            abort(403);
        }

        $req = RequestStok::findOrFail($id);
        $note = trim((string) $req->catatan);
        if ($note !== '') {
            $note .= ' | Ditolak pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        } else {
            $note = 'Ditolak pada ' . now('Asia/Jakarta')->format('d/m/Y H:i');
        }

        $req->catatan = $note;
        $req->status = 'ditolak';
        // $req->disetujui_oleh = null;
        // $req->waktu_disetujui = null;
        $req->save();

        return back()->with('success', 'Permintaan stok ditolak');
    }
}
