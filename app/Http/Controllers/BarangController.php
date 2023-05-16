<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        return view('barang.index');
    }
    public function ajaxIndex()
    {
        $barangs = Barang::query();

        return DataTables::of($barangs)->toJson();
    }
}
