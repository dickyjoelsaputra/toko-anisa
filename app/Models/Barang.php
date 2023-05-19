<?php

namespace App\Models;

use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'nama', 'kode', 'manual', 'gambar', 'satuan_id'
    ];

    public function satuans()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }

    public function hargas()
    {
        return $this->hasMany(Harga::class, 'barang_id', 'id');
    }
}