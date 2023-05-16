<?php

namespace App\Models;

use App\Models\Harga;
use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama', 'gambar', 'kode', 'manual', 'satuan_id'
    ];

    public function hargas()
    {
        return $this->hasMany(Harga::class, 'barang_id', 'id');
    }

    public function satuans()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }
}
