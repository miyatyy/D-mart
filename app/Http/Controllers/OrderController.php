<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. Memproses transaksi pembelian produk
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stok < $request->jumlah) {
            return back()->with('error', 'Maaf, stok produk ini tidak mencukupi!');
        }

        $totalHarga = $product->harga_jual * $request->jumlah;

        Order::create([
            'pembeli_id' => Auth::id(),
            'product_id' => $product->id,
            'jumlah' => $request->jumlah,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pesanan' => 'menunggu_konfirmasi',
        ]);

        $product->decrement('stok', $request->jumlah);

        return redirect('/home')->with('success', 'Pesanan berhasil dikirim!');
    }

    // 2. Update status pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:menunggu_konfirmasi,diproses,selesai'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status_pesanan' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}