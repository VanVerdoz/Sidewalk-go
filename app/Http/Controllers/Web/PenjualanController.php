<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\Stok;
use App\Models\ClosingHarian;
use App\Models\RequestStok;

class PenjualanController extends Controller
{
    public function index()
    {
            $userId = session('user.id');
            $role = session('user.role');
            $selectedCabangId = request('cabang_id');
            $cabangList = Cabang::orderBy('id', 'asc')->get();

            $query = Penjualan::with(['cabang', 'pengguna', 'detail_penjualan.produk'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->where(function ($q) {
                    $q->whereNull('metode_pembayaran')
                      ->orWhere('metode_pembayaran', '!=', 'request_stok');
                })
                ->when(!empty($selectedCabangId) && in_array($role, ['admin','owner']), function ($q) use ($selectedCabangId) {
                    $q->where('cabang_id', $selectedCabangId);
                });

            // Raider hanya melihat transaksi miliknya sendiri dan hanya hari ini
            if ($role === 'raider' && $userId) {
                $query->where('pengguna_id', $userId)
                      ->whereDate('tanggal', today());
            }

            $penjualan = $query->get();

            // Rekap penjualan harian khusus untuk raider
            $totalPendapatanHariIni = null;
            $totalProdukHariIni = null;
            $monitorWarningCabang = null;
            $monitorOkCabang = null;

            if ($role === 'raider' && $userId) {
                $totalPendapatanHariIni = Penjualan::where('pengguna_id', $userId)
                    ->whereDate('tanggal', today())
                    ->where(function ($q) {
                        $q->whereNull('metode_pembayaran')
                          ->orWhere('metode_pembayaran', '!=', 'request_stok');
                    })
                    ->sum('total');

                $totalProdukHariIni = DetailPenjualan::whereHas('penjualan', function ($q) use ($userId) {
                    $q->where('pengguna_id', $userId)
                        ->whereDate('tanggal', today())
                        ->where(function ($q2) {
                            $q2->whereNull('metode_pembayaran')
                               ->orWhere('metode_pembayaran', '!=', 'request_stok');
                        });
                })->sum('jumlah');

                // Monitoring konsistensi cabang untuk hari ini
                $counts = $penjualan->groupBy('cabang_id')->map->count();
                $expectedCabangId = $counts->sortDesc()->keys()->first();
                $wrongBranches = [];

                foreach ($penjualan as $row) {
                    if ((string) $row->cabang_id !== (string) $expectedCabangId) {
                        if ($row->cabang) {
                            $wrongBranches[] = $row->cabang->nama_cabang;
                        } else {
                            $wrongBranches[] = $row->cabang_id;
                        }
                    }
                }
                
                $wrongBranches = array_unique($wrongBranches);

                if (!empty($wrongBranches)) {
                    $branchStr = implode(', ', $wrongBranches);
                    $monitorWarningCabang = "Peringatan cabang anda salah, ubah cabang $branchStr anda";
                } elseif ($penjualan->count() > 0) {
                    $monitorOkCabang = 'Mantap transaksi anda berhasil';
                }
            }

            // Rekap harian per cabang (admin & owner)
            $rekapCabangHariIni = collect();
            $grandTotalHariIni = null;
            if (in_array($role, ['admin','owner'])) {
                if (empty($selectedCabangId)) {
                    $grandTotalHariIni = Penjualan::whereDate('tanggal', today())
                        ->where(function ($q) {
                            $q->whereNull('metode_pembayaran')
                              ->orWhere('metode_pembayaran', '!=', 'request_stok');
                        })
                        ->sum('total');
                } else {
                    $rekapCabangHariIni = Penjualan::whereDate('tanggal', today())
                        ->where(function ($q) {
                            $q->whereNull('metode_pembayaran')
                              ->orWhere('metode_pembayaran', '!=', 'request_stok');
                        })
                        ->where('cabang_id', $selectedCabangId)
                        ->select('cabang_id', DB::raw('COUNT(*) as transaksi'), DB::raw('SUM(total) as total'))
                        ->groupBy('cabang_id')
                        ->with('cabang')
                        ->get();
                }
            }

            return view('penjualan.index', compact(
                'penjualan',
                'totalPendapatanHariIni',
                'totalProdukHariIni',
                'cabangList',
                'selectedCabangId',
                'rekapCabangHariIni',
                'grandTotalHariIni',
                'monitorWarningCabang',
                'monitorOkCabang'
            ));
    }

    public function create()
    {
        $produk = Produk::where('status', 'aktif')->get();
        $cabang = Cabang::all();
        $currentCabangId = null;
        if (session('user.role') === 'raider' && session('user.id')) {
            $currentCabangId = RequestStok::where('raider_id', (int) session('user.id'))
                ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
                ->orderBy('tanggal', 'desc')
                ->value('cabang_id');
        }
        return view('penjualan.create', compact('produk', 'cabang', 'currentCabangId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'tanggal' => 'required|date',
            'total' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
            'produk_id' => 'nullable|exists:produk,id',
        ]);

        if (session('user.role') === 'raider' && session('user.id')) {
            $currentCabangId = RequestStok::where('raider_id', (int) session('user.id'))
                ->whereDate('tanggal', now('Asia/Jakarta')->toDateString())
                ->orderBy('tanggal', 'desc')
                ->value('cabang_id');
            if (!empty($currentCabangId) && (string) $request->cabang_id !== (string) $currentCabangId) {
                return back()->withErrors([
                    'cabang_id' => 'Input sesuai lokasi cabang anda saat ini',
                ])->withInput();
            }
        }

        $produk = null;
        if ($request->filled('produk_id')) {
            $produk = Produk::find($request->produk_id);
        }

        $keterangan = $request->keterangan;
        if ((!$keterangan || trim($keterangan) === '') && $produk && $produk->deskripsi) {
            $keterangan = $produk->deskripsi;
        }

        $penjualan = Penjualan::create([
            'cabang_id' => $request->cabang_id,
            'pengguna_id' => session('user.id'),
            'tanggal' => $request->tanggal,
            'total' => $request->total,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $keterangan,
            'dibuat_oleh' => session('user.id'),
        ]);

        if ($produk) {
            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'produk_id' => $produk->id,
                'jumlah' => 1,
                'harga' => $produk->harga,
            ]);
        }

        return redirect()->route('penjualan.index');
    }

    public function show(string $id)
    {
        $penjualan = Penjualan::with(['cabang', 'pengguna', 'detail_penjualan.produk'])->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    public function edit(string $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $produk = Produk::where('status', 'aktif')->get();
        $cabang = Cabang::all();
        return view('penjualan.edit', compact('penjualan', 'produk', 'cabang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'tanggal' => 'required|date',
            'total' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->update([
            'cabang_id' => $request->cabang_id,
            'tanggal' => $request->tanggal,
            'total' => $request->total,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $role = session('user.role');
        $userId = session('user.id');
        $penjualan = Penjualan::with('detail_penjualan')->findOrFail($id);

        if (in_array($role, ['admin', 'owner'])) {
            $penjualan->detail_penjualan()->delete();
            $penjualan->delete();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dihapus');
        }

        if ($role === 'raider') {
            $isOwner = (string) $penjualan->pengguna_id === (string) $userId;
            $isToday = \Carbon\Carbon::parse($penjualan->tanggal)->isSameDay(\Carbon\Carbon::today());
            $isAllowedType = empty($penjualan->metode_pembayaran) || $penjualan->metode_pembayaran !== 'request_stok';

            if (!$isOwner || !$isToday || !$isAllowedType) {
                abort(403);
            }

            $penjualan->detail_penjualan()->delete();
            $penjualan->delete();
            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil dihapus');
        }

        abort(403);
    }

    public function sisaHariIniForm(Request $request)
    {
        if (session('user.role') !== 'raider') {
            abort(403);
        }
        $cabangList = Cabang::orderBy('id', 'asc')->get();
        $selectedCabangId = $request->query('cabang_id');
        $stok = collect();
        if (!empty($selectedCabangId)) {
            $stok = Stok::with('produk')
                ->where('cabang_id', (int)$selectedCabangId)
                ->where('jumlah', '>', 0)
                ->get();
        }
        return view('raider.sisa-hari-ini', compact('cabangList', 'selectedCabangId', 'stok'));
    }

    public function sisaHariIniStore(Request $request)
    {
        if (session('user.role') !== 'raider') {
            abort(403);
        }
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'sisa' => 'required|array|min:1',
            'sisa.*' => 'required|integer|min:0',
        ]);

        $penggunaId = (int) (session('user.id'));
        $tanggal = now('Asia/Jakarta')->toDateString();

        $detail = [];
        foreach ($request->produk_id as $idx => $pid) {
            $detail[] = [
                'produk_id' => (int) $pid,
                'sisa' => (int) ($request->sisa[$idx] ?? 0),
            ];
        }

        $totalPenjualan = Penjualan::where('cabang_id', (int)$request->cabang_id)
            ->whereDate('tanggal', $tanggal)
            ->sum('total');

        ClosingHarian::create([
            'cabang_id' => (int)$request->cabang_id,
            'pengguna_id' => $penggunaId,
            'tanggal' => $tanggal,
            'total_penjualan' => $totalPenjualan,
            'stok_akhir' => json_encode(['detail' => $detail]),
            'created_at' => now('Asia/Jakarta'),
        ]);

        $belumLaku = 0;
        // Hitung jumlah produk yang sisa == stok awal (indikasi belum laku)
        $stokCabang = Stok::where('cabang_id', (int)$request->cabang_id)->get()->keyBy('produk_id');
        foreach ($detail as $row) {
            $awal = (int) ($stokCabang[$row['produk_id']]->jumlah ?? 0);
            if ($awal > 0 && $row['sisa'] === $awal) {
                $belumLaku++;
            }
        }

        return redirect()->route('raider.sisa-hari-ini.form', ['cabang_id' => $request->cabang_id])
            ->with('success', "Sisa hari ini tersimpan. Ada {$belumLaku} produk belum laku hari ini.");
    }
}
