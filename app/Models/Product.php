<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi data lewat form input nanti
    protected $fillable = [
    'user_id', // PASTIKAN BARIS INI ADA
    'nama_produk',
    'harga_modal',
    'harga_jual',
    'stok',
    'kategori',
    'lokasi_spesifik',
    'gambar'
];

    // Hubungan: Satu produk HANYA DIMILIKI OLEH SATU User (Seller)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}