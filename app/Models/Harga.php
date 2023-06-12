<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Keranjang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Harga extends Model
{
    use HasFactory;

    protected $table = 'hargas';

    protected $fillable = [
        'harga', 'barang_id'
    ];

    public function barangs()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'harga_id', 'id');
    }
}
