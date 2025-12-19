<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\LaporanKeuangan;
use App\Models\Cabang;
use App\Models\Pengguna;
use App\Models\RequestStok;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = session('user');
            $role = $user['role'] ?? '';

            // Normalisasi role: lowercase dan ganti spasi/hyphen menjadi underscore
            $normalized = strtolower(str_replace(['-', ' '], '_', (string) $role));

            // Route ke dashboard sesuai role (lebih toleran terhadap variasi penamaan)
            switch ($normalized) {
                case 'owner':
                    return $this->ownerDashboard();
                case 'kepala_gudang':
                case 'kepalagudang':
                case 'gudang':
                    return $this->kepalaGudangDashboard();
                case 'raider':
                    return $this->raiderDashboard();
                case 'admin':
                    return $this->adminDashboard();
                default:
                    // Heuristik: jika string mengandung 'gudang', arahkan ke dashboard kepala gudang
                    if (strpos($normalized, 'gudang') !== false) {
                        return $this->kepalaGudangDashboard();
                    }
                    return $this->defaultDashboard();
            }
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return view with empty data to prevent crash
            return view('dashboard.index', [
                'totalPenjualan' => 0,
                'stokTersedia' => 0,
                'transaksiHarian' => 0,
                'chartLabels' => ['Error loading data'],
                'chartData' => [0],
                'role' => session('user.role') ?? 'guest'
            ]);
        }
    }

    // Dashboard untuk Owner - Lihat semua aktivitas
    private function ownerDashboard()
    {
	        $totalPenjualan = Penjualan::where(function ($q) {
	                $q->whereNull('metode_pembayaran')
	                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
	            })
	            ->sum('total') ?? 0;
        $stokTersedia = Stok::sum('jumlah') ?? 0;
	        $transaksiHarian = Penjualan::whereDate('tanggal', today())
	            ->where(function ($q) {
	                $q->whereNull('metode_pembayaran')
	                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
	            })
	            ->count();
        $totalCabang = Cabang::count();
        $totalPengguna = Pengguna::count();

        // Top selling products
	        $topProducts = DetailPenjualan::whereHas('penjualan', function ($q) {
	                $q->whereNull('metode_pembayaran')
	                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
	            })
	            ->select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
	            ->groupBy('produk_id')
	            ->orderBy('total_terjual', 'desc')
	            ->limit(7)
	            ->with('produk')
	            ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($topProducts as $item) {
            $chartLabels[] = $item->produk ? $item->produk->nama_produk : 'Unknown';
            $chartData[] = (int) $item->total_terjual;
        }

        if (empty($chartLabels)) {
            $chartLabels = ['Tidak ada data'];
            $chartData = [0];
        }

        // Penjualan per hari (7 hari terakhir) untuk grafik tren
        $penjualanPerHari = Penjualan::whereBetween('tanggal', [now()->subDays(6), now()])
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->selectRaw('DATE(tanggal) as tanggal, SUM(total) as total')
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $lineLabels = [];
        $lineData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $lineLabels[] = now()->subDays($i)->format('d M');
            $found = $penjualanPerHari->firstWhere('tanggal', $date);
            $lineData[] = $found ? (int) $found->total : 0;
        }

        return view('dashboard.owner', compact(
            'totalPenjualan',
            'stokTersedia',
            'transaksiHarian',
            'totalCabang',
            'totalPengguna',
            'chartLabels',
            'chartData',
            'lineLabels',
            'lineData'
        ));
    }

    // Dashboard untuk Kepala Gudang - Fokus ke stok
    private function kepalaGudangDashboard()
    {
        $stokTersedia = Stok::sum('jumlah') ?? 0;
        $totalProduk = Produk::count();
        $produkHabis = Stok::where('jumlah', '<=', 10)->count();
        $produkAktif = Produk::where('status', 'aktif')->count();

        $stokRendah = Stok::with('produk')
            ->where('jumlah', '<=', 10)
            ->orderBy('jumlah', 'asc')
            ->limit(5)
            ->get();

        $selectedCabangId = request('cabang_id');
        $cabangList = Cabang::orderBy('id', 'asc')->get();

        $today = now('Asia/Jakarta')->toDateString();
        $totalPenjualanHariIni = Penjualan::whereDate('tanggal', $today)->sum('total') ?? 0;
        $stokPercentProduk = $totalProduk > 0 ? round(($produkAktif / $totalProduk) * 100) : 0;

        $topProdukTerjual = DetailPenjualan::whereHas('penjualan', function ($q) use ($selectedCabangId, $today) {
                $q->whereDate('tanggal', $today)
                  ->where(function ($q2) {
                      $q2->whereNull('metode_pembayaran')
                         ->orWhere('metode_pembayaran', '!=', 'request_stok');
                  });
                if (!empty($selectedCabangId)) {
                    $q->where('cabang_id', $selectedCabangId);
                }
            })
            ->whereHas('penjualan.pengguna', function ($q) {
                $q->where('role', 'raider');
            })
            ->select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('produk_id')
            ->orderBy('total_terjual', 'desc')
            ->limit(12)
            ->with('produk')
            ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($topProdukTerjual as $item) {
            $chartLabels[] = $item->produk ? $item->produk->nama_produk : 'Unknown';
            $chartData[] = (int) $item->total_terjual;
        }

        if (empty($chartLabels)) {
            $chartLabels = ['Tidak ada data'];
            $chartData = [0];
        }

        // Rekap harian dipindahkan ke halaman Produk Terjual

        return view('dashboard.kepala-gudang', compact(
            'stokTersedia',
            'totalProduk',
            'produkHabis',
            'stokRendah',
            'chartLabels',
            'chartData',
            'cabangList',
            'selectedCabangId',
            'totalPenjualanHariIni',
            'stokPercentProduk'
        ));
    }

    // Halaman khusus: Produk Terjual (Kepala Gudang)
    public function kepalaGudangProdukTerjual()
    {
        $selectedCabangId = request('cabang_id');
        $cabangList = Cabang::orderBy('id', 'asc')->get();
        $today = now('Asia/Jakarta')->toDateString();

        $rows = DetailPenjualan::whereHas('penjualan.pengguna', function ($q) {
                $q->where('role', 'raider');
            })
            ->whereHas('penjualan', function ($q) use ($selectedCabangId, $today) {
                $q->whereDate('tanggal', $today);
                if (!empty($selectedCabangId)) {
                    $q->where('cabang_id', $selectedCabangId);
                }
            })
            ->with(['produk', 'penjualan.pengguna'])
            ->orderBy('penjualan_id', 'desc')
            ->limit(100)
            ->get();

        $rekapProdukHariIni = DetailPenjualan::whereHas('penjualan', function ($q) use ($selectedCabangId, $today) {
                $q->whereDate('tanggal', $today)
                  ->where(function ($q2) {
                      $q2->whereNull('metode_pembayaran')
                         ->orWhere('metode_pembayaran', '!=', 'request_stok');
                  });
                if (!empty($selectedCabangId)) {
                    $q->where('cabang_id', $selectedCabangId);
                }
            })
            ->whereHas('penjualan.pengguna', function ($q) {
                $q->where('role', 'raider');
            })
            ->select('produk_id', DB::raw('SUM(jumlah) as qty'))
            ->groupBy('produk_id')
            ->orderBy('qty', 'desc')
            ->with('produk')
            ->get();

        $produkTerlarisNama = $rekapProdukHariIni->first() && $rekapProdukHariIni->first()->produk
            ? $rekapProdukHariIni->first()->produk->nama_produk
            : 'Tidak ada';
        $produkTerlarisQty = (int) ($rekapProdukHariIni->first()->qty ?? 0);

        $top3Terlaris = $rekapProdukHariIni->take(3);

        return view('dashboard.produk-terjual', compact('rows', 'cabangList', 'selectedCabangId', 'rekapProdukHariIni', 'produkTerlarisNama', 'produkTerlarisQty', 'top3Terlaris'));
    }

    // Dashboard untuk Raider - Fokus ke transaksi
    private function raiderDashboard()
    {
        $userId = session('user.id');

        $transaksiHarian = Penjualan::where('pengguna_id', $userId)
            ->whereDate('tanggal', today())
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->count();

        $totalPenjualanHariIni = Penjualan::where('pengguna_id', $userId)
            ->whereDate('tanggal', today())
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->sum('total') ?? 0;

        $transaksiMingguIni = Penjualan::where('pengguna_id', $userId)
            ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->count();

        // Chart penjualan per hari (7 hari terakhir)
        $penjualanPerHari = Penjualan::where('pengguna_id', $userId)
            ->whereBetween('tanggal', [now()->subDays(6), now()])
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->selectRaw('DATE(tanggal) as tanggal, SUM(total) as total')
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');

            $found = $penjualanPerHari->firstWhere('tanggal', $date);
            $chartData[] = $found ? (int) $found->total : 0;
        }

        return view('dashboard.raider', compact(
            'transaksiHarian',
            'totalPenjualanHariIni',
            'transaksiMingguIni',
            'chartLabels',
            'chartData'
        ));
    }

    // Dashboard untuk Admin - Fokus ke laporan keuangan
    private function adminDashboard()
    {
        $totalPenjualan = Penjualan::where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->sum('total') ?? 0;
        $transaksiHarian = Penjualan::whereDate('tanggal', today())
            ->where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->count();
        $totalLaporan = LaporanKeuangan::count();

        // Chart penjualan per bulan (6 bulan terakhir)
        $penjualanPerBulan = Penjualan::where(function ($q) {
                $q->whereNull('metode_pembayaran')
                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
            })
            ->selectRaw('EXTRACT(MONTH FROM tanggal) as bulan, SUM(total) as total')
            ->whereBetween('tanggal', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal)'))
            ->orderBy('bulan', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->month;
            $chartLabels[] = now()->subMonths($i)->format('M Y');

            $found = $penjualanPerBulan->firstWhere('bulan', $month);
            $chartData[] = $found ? (int) $found->total : 0;
        }

        if (empty($chartLabels)) {
            $chartLabels = ['Tidak ada data'];
            $chartData = [0];
        }

        return view('dashboard.admin', compact(
            'totalPenjualan',
            'transaksiHarian',
            'totalLaporan',
            'chartLabels',
            'chartData'
        ));
    }

    // Default dashboard
    private function defaultDashboard()
    {
	        $totalPenjualan = Penjualan::where(function ($q) {
	                $q->whereNull('metode_pembayaran')
	                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
	            })
	            ->sum('total') ?? 0;
        $stokTersedia = Stok::sum('jumlah') ?? 0;
	        $transaksiHarian = Penjualan::whereDate('tanggal', today())
	            ->where(function ($q) {
	                $q->whereNull('metode_pembayaran')
	                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
	            })
	            ->count();

	        $topProducts = DetailPenjualan::whereHas('penjualan', function ($q) {
	                $q->whereNull('metode_pembayaran')
	                  ->orWhere('metode_pembayaran', '!=', 'request_stok');
	            })
	            ->select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
	            ->groupBy('produk_id')
	            ->orderBy('total_terjual', 'desc')
	            ->limit(7)
	            ->with('produk')
	            ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($topProducts as $item) {
            $chartLabels[] = $item->produk ? $item->produk->nama_produk : 'Unknown';
            $chartData[] = (int) $item->total_terjual;
        }

        if (empty($chartLabels)) {
            $chartLabels = ['Tidak ada data'];
            $chartData = [0];
        }

        return view('dashboard.index', compact(
            'totalPenjualan',
            'stokTersedia',
            'transaksiHarian',
            'chartLabels',
            'chartData'
        ));
    }
}
