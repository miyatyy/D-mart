<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // ← PASTIKAN NAMANYA SEPERTI INI
    $table->foreignId('pembeli_id')->constrained('users')->onDelete('cascade');
    $table->integer('jumlah');
    $table->integer('total_harga');
    $table->string('metode_pembayaran');
    $table->string('status_pesanan');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};