<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ads', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Pemilik iklan
        $table->string('judul_iklan');
        $table->string('gambar_iklan');
        $table->integer('klik')->default(0);
        $table->enum('status', ['aktif', 'berhenti'])->default('aktif');
        $table->timestamps();
    });
}
};
