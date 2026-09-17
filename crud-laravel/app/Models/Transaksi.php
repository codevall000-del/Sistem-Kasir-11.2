<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['transaksi_id', 'no_nota', 'total_harga', 'bayar', 'kembali'];
}