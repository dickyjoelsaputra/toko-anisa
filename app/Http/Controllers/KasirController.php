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

    // scanner search like search
    public function scan(Request $request)
    {
        $scan = $request->input('scan');
        $dataArray = $request->input('dataarray') ?? [];
        $barang = Barang::whereNotIn('id', $dataArray)
            ->where(function ($query) use ($scan) {
                $query->where('nama', 'like', "$scan%")
                    ->orWhere('kode', 'like', "$scan%");
            })->with(['satuans', 'hargas'])
            ->first();

        return response()->json(['results' => $barang, 'results2' => $dataArray]);
    }


    public function search(Request $request)
    {
        $searchText = $request->input('search');
        $dataArray = $request->input('dataarray') ?? [];
        $barang = Barang::whereNotIn('id', $dataArray)
            ->where(function ($query) use ($searchText) {
                $query->where('nama', 'like', "$searchText%")
                    ->orWhere('kode', 'like', "$searchText%");
            })->with(['satuans', 'hargas'])
            ->get();
        // ->where('nama', 'like', "$searchText%")
        // ->orWhere('kode', 'like', "$searchText%")
        // ->get();

        return response()->json(['results' => $barang, 'results2' => $dataArray]);
    }
}
