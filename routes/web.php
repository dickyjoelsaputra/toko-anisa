<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\DashboardController;
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

Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(
    function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard-index');
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir-index');

        Route::get('/user', [UserController::class, 'index'])->name('user-index');

        Route::get('/barang', [BarangController::class, 'index'])->name('barang-index');
        Route::get('/barang/json', [BarangController::class, 'ajaxIndex'])->name('barang-ajax-index');
        Route::get('/barang/add/komputer', [BarangController::class, 'createKomputer'])->name('barang-create-komputer');
        Route::get('/barang/add/hp', [BarangController::class, 'createHp'])->name('barang-create-hp');
        Route::get('/barang/{id}', [BarangController::class, 'edit'])->name('barang-edit');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang-destroy');

        Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan-index');
        Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan-store');
        Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan-destroy');
    }
);
