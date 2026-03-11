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
        Schema::create('pesanan_makanan_header', function (Blueprint $table) {
            $table->id('id_pesanan_makanan');
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_kost');
            $table->decimal('total_harga', 15, 2);
            $table->integer('total_item');
            $table->enum('status_antar', ['menunggu_bayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu_bayar');
            $table->text('catatan')->nullable();
            $table->string('orderan_id', 100)->nullable();
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

            // Indexes
            $table->index('status_antar');
            $table->index('orderan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_makanan_header');
    }
};
