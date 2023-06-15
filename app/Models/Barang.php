<?php

namespace App\Models;

use App\Models\Harga;
use App\Models\Satuan;
use App\Models\Keranjang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'nama', 'kode', 'manual', 'gambar', 'satuan_id', 'harga'
    ];

    public function satuans()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'barang_id', 'id');
    }
}
