<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/register', function () {
//     $data['nama'] = 'a';
//     $data['password'] = Hash::make('m');
//     $data['role'] = 'y';
//     // $user = User::create($data);
// });

Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(
    function () {
        // DASHBOARD
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard-index');
        Route::get('/ajaxchart', [DashboardController::class, 'ajaxChart'])->name('dashboard-ajaxchart');

        // KASIR
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir-index');
        Route::get('/kasir/{id}', [KasirController::class, 'getOne'])->name('kasir-getone');
        Route::post('/kasir/search', [KasirController::class, 'search'])->name('kasir-search');
        Route::post('/kasir/scan', [KasirController::class, 'scan'])->name('kasir-scan');

        // USER
        Route::get('/user', [UserController::class, 'index'])->name('user-index');

        // BARANG
        Route::get('/barang', [BarangController::class, 'index'])->name('barang-index');
        Route::get('/barang/json', [BarangController::class, 'ajaxIndex'])->name('barang-ajax-index');
        Route::get('/barang/add/komputer', [BarangController::class, 'createKomputer'])->name('barang-create-komputer');
        Route::post('/barang/store/komputer', [BarangController::class, 'storeKomputer'])->name('barang-store-komputer');
        Route::get('/barang/add/hp', [BarangController::class, 'createHp'])->name('barang-create-hp');
        Route::post('/barang/store/hp', [BarangController::class, 'storeHp'])->name('barang-store-hp');
        Route::get('/barang/{id}', [BarangController::class, 'edit'])->name('barang-edit');
        Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang-update');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang-destroy');
        Route::get('/barang-print/{id}', [BarangController::class, 'print'])->name('barang-print');

        // SATUAN
        Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan-index');
        Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan-store');
        Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan-destroy');

        // TRANSAKSI
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi-index');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi-store');
    }
);
