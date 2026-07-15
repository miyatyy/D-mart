<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = (object) ['lokasi_kampus' => 'Banjarbaru'];

        // 2. Ambil lokasi kampus user (Banjarmasin / Banjarbaru)
        $lokasiKampus = $user->lokasi_kampus;

        // 3. Query Postgres: Ambil produk yang penjualnya berada di kampus yang sama
        // Menggunakan relasi tabel users dan products
       $products = Product::whereHas('user', function($query) use ($lokasiKampus) {
    $query->where('lokasi_kampus', $lokasiKampus);
})->get();

        // 4. Lempar data produk ke tampilan web (view)
        return view('home', compact('products', 'lokasiKampus'));
    }
}