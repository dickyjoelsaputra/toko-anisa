<?php

namespace App\Http\Controllers;

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
}
