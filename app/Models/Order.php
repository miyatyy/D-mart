<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database PostgreSQL (opsional, jika Laravel otomatis menjamakkan menjadi 'orders')
    protected $table = 'orders';

    // Mendaftarkan kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'pembeli_id',
        'product_id',
        'jumlah',
        'total_harga',
        'metode_pembayaran',
        'status_pesanan',
        'gambar'
    ];

    /**
     * Relasi ke model User (Sebagai Pembeli)
     * Menghubungkan kolom pembeli_id di tabel orders dengan id di tabel users
     */
    public function pembeli()
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    /**
     * Relasi ke model Product (Produk yang dibeli)
     * Menghubungkan kolom product_id di tabel orders dengan id di tabel products
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}