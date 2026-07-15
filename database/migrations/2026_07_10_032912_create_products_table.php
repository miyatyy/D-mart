<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Penjual (Hubungan ke tabel Users)
            $table->string('nama_produk');
            $table->decimal('harga_modal', 15, 2); // Tipe data finansial akurat di Postgres
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok')->default(0);
            $table->text('lokasi_spesifik'); // Contoh: "Gedung Tarbiyah Lt. 2"
            $table->string('kategori'); // 'koperasi', 'makanan', 'jasa', dll.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};