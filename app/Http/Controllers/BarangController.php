<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
// use DataTables;

class BarangController extends Controller
{
    public function index()
    {
        return view('barang.index');
    }
    public function ajaxIndex()
    {
        $barangs = Barang::with('hargas', 'satuans')->get();
        return DataTables::of($barangs)
            ->addColumn('harga', function ($barang) {
                $harga = '<table style="width: 100%">';
                $harga .= '<tr>';
                $harga .= '<th>Minimal</th>';
                $harga .= '<th>Harga</th>';
                $harga .= '<tr>';
                foreach ($barang->hargas as $hargaItem) {
                    $harga .= '<tr>';
                    $harga .= '<td>' . $hargaItem->minimal . '</td>';
                    $harga .= '<td>' . $hargaItem->harga . '</td>';
                    $harga .= '</tr>';
                }
                $harga .= '</table>';
                return $harga;
            })
            ->rawColumns(['harga'])
            ->toJson();
    }
    public function createKomputer()
    {
        $satuans = Satuan::get();
        return view('barang.create-komputer', ['satuans' => $satuans]);
    }
    public function storeKomputer(Request $request)
    {
        return response()->json($request->all());
    }


    public function createHp()
    {
        return view('barang.create-hp');
    }

    public function edit($id)
    {
        return view('barang.edit');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->hargas()->delete();
        $barang->delete();
        return redirect(route('barang-index'));
    }
}
