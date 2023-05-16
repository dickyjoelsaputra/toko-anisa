<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::all();
        return view('satuan.index', ['satuans' => $satuans]);
    }


    public function store(Request $request)
    {
        $request["nama"] = strtoupper($request["nama"]);
        $request["alias"] = strtoupper($request["alias"]);
        Satuan::create($request->all());
    }

    public function destroy($id)
    {
        $deleteSatuan = Satuan::findOrFail($id);
        $deleteSatuan->delete();
    }
}
