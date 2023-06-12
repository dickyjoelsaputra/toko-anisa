<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['keranjangs.barangs.hargas', 'users'])->latest()->get();
        return view('transaksi.index', ['transaksis' => $transaksis]);
    }

    public function store(Request $request)
    {
        $transaksi = Transaksi::create([
            'user_id' => Auth()->user()->id,
            'total' => intval(str_replace(".", "", $request->total))
        ]);

        foreach ($request->dataTransaksi as $barang) {
            $keranjang = Keranjang::create([
                'keranjang_harga' => intval(str_replace(".", "", $barang['total'])),
                'jumlah' => $barang['qty'],
                'barang_id' => $barang['id'],
                'harga_id' => $barang['harga_id'],
                'transaksi_id' => $transaksi->id,
            ]);
        }

        return response()->json(['results' => $request->all()]);
    }
}
