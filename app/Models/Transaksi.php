<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;


    protected $table = 'transaksis';

    protected $fillable = [
        'total', 'user_id', 'uangpembeli', 'kembalian',
    ];

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'transaksi_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
