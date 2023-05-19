<?php

namespace App\Models;

use App\Models\Harga;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keranjang extends Model
{
    use HasFactory;


    protected $table = 'keranjangs';

    protected $fillable = [
        'keranjang_harga',
        'jumlah',
        'barang_id',
        'harga_id',
        'transaksi_id',
    ];

    public function barangs()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
    public function hargas()
    {
        return $this->belongsTo(Harga::class, 'harga_id', 'id');
    }
    public function transaksis()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id');
    }
}
