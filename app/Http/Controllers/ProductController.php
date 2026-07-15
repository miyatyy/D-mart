<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 1. Menampilkan halaman formulir tambah produk
    public function create()
    {
        return view('products.create');
    }

    // Di dalam ProductController.php
public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required|string',
        'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $namaGambar = null;
    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $namaGambar = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads/products'), $namaGambar);
    }

    Product::create([
        'user_id' => Auth::id(),
        'nama_produk' => $request->nama_produk,
        'harga_modal' => $request->harga_modal,
        'harga_jual' => $request->harga_jual,
        'stok' => $request->stok,
        'kategori' => $request->kategori,
        'lokasi_spesifik' => $request->lokasi_spesifik,
        'gambar' => $namaGambar,
    ]);

    return redirect('/home')->with('success', 'Produk berhasil ditambahkan!');
}

    // 3. Menghapus data produk dan file gambarnya dari sistem
    public function destroy($id)
    {
        // Cari produk berdasarkan ID dan pastikan milik user yang sedang login
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Menghapus file fisik gambar langsung dari folder public/uploads/products
        if ($product->gambar) {
            $pathGambar = public_path('uploads/products/' . $product->gambar);
            if (file_exists($pathGambar)) {
                unlink($pathGambar);
            }
        }

        // Hapus data dari database PostgreSQL
        $product->delete();

        return redirect('/home')->with('success', 'Produk jualanmu berhasil dihapus!');
    }
}