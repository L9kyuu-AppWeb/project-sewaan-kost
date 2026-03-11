<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan_makanan_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pesanan_makanan');
            $table->unsignedBigInteger('id_makanan');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->text('catatan_item')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_pesanan_makanan')
                ->references('id_pesanan_makanan')
                ->on('pesanan_makanan_header')
                ->onDelete('cascade');

            $table->foreign('id_makanan')
                ->references('id_makanan')
                ->on('makanan')
                ->onDelete('cascade');

            // Indexes
            $table->index('id_pesanan_makanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_makanan_detail');
    }
};
