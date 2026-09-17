<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            // Menyambungkan ke tabel transaksis
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('qty');
            $table->integer('harga');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_transaksis');
    }
};