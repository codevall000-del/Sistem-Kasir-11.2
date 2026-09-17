<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
    'transaksi_id', 
    'nama_barang', // Pastikan menggunakan nama kolom yang benar sesuai error
    'qty', 
    'harga',
    // tambahkan kolom lain jika ada
    ];
}