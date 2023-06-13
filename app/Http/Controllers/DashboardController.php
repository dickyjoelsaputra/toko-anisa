<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // sum total from model transaksi
        $labakotor = Transaksi::sum('total');
        $jumlahtransaksi = Transaksi::count();
        $jumlahbarang = Barang::count();

        return view('dashboard.index', ['labakotor' => $labakotor, 'jumlahtransaksi' => $jumlahtransaksi, 'jumlahbarang' => $jumlahbarang]);
    }

    public function ajaxChart()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $transaksi = Transaksi::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, sum(total) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $formattedTransaksi = collect([]);

        // Menambahkan semua bulan dengan total 0
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $startMonth = $currentMonth - 11;
        $startYear = $currentYear;
        if ($startMonth <= 0) {
            $startMonth += 12;
            $startYear -= 1;
        }

        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::create()->setYear($startYear)->month($startMonth)->format('M Y');
            $transaksiData = $transaksi->first(function ($item) use ($startMonth, $startYear) {
                return $item->month == $startMonth && $item->year == $startYear;
            });

            $formattedTransaksi->push([
                'month' => $month,
                'total' => $transaksiData ? $transaksiData->total : '0',
            ]);

            $startMonth += 1;
            if ($startMonth > 12) {
                $startMonth = 1;
                $startYear += 1;
            }
        }

        return response()->json($formattedTransaksi);
    }
}
