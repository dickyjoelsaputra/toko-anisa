<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Builder;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.index');
    }

    public function getOne($id)
    {
        $barang = Barang::with(['satuans', 'hargas'])->findOrFail($id);

        return response()->json(['results' => $barang]);
    }

    public function search(Request $request)
    {
        $searchText = $request->input('search');
        // $dataArray = $request->input('dataarray');
        // ->orWhere('id', '!=', $dataArray)

        $barang = Barang::where('nama', 'like', "$searchText%")
            ->orWhere('kode', 'like', "$searchText%")
            ->get();

        return response()->json(['results' => $barang]);
        // return response()->json(['results' => $barang, 'results2' => $dataArray]);
    }
}
