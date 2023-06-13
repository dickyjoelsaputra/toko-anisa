<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        // $date = $request->date;
        // $date = $request->query('date');

        $transaksis = Transaksi::with(['keranjangs.barangs.hargas', 'users'])
            ->when($date, function ($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->orderByDesc('created_at')->paginate(2);

        return view('transaksi.index', ['transaksis' => $transaksis, 'date' => $date]);
    }

    public function store(Request $request)
    {

        $transaksi = Transaksi::create([
            'user_id' => Auth()->user()->id,
            'total' => intval(str_replace(".", "", $request->total)),
            'uangpembeli' => intval(str_replace(".", "", $request->uangpembeli)),
            'kembalian' => intval(str_replace(".", "", $request->kembalian))
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

        return response()->json(['status' => 'success', 'message' => 'Transaksi Berhasil di proses']);
    }
}
