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
        Schema::create('pesanan_makan', function (Blueprint $table) {
            $table->id('id_order_makan');
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_kost');
            $table->unsignedBigInteger('id_makanan');
            $table->integer('jumlah');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status_antar', ['menunggu_bayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu_bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_penyewa')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_kost')
                ->references('id_kost')
                ->on('kost')
                ->onDelete('cascade');

            $table->foreign('id_makanan')
                ->references('id_makanan')
                ->on('makanan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_makan');
    }
};
