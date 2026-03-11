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
        Schema::create('pesanan_galon', function (Blueprint $table) {
            $table->id('id_order_galon');
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_kost');
            $table->unsignedBigInteger('id_galon_tipe');
            $table->string('foto_kosong', 255); // Wajib: foto galon kosong dari penyewa
            $table->string('foto_terisi', 255)->nullable(); // Wajib: foto galon terisi dari pemilik
            $table->enum('status_galon', ['menunggu_bayar', 'diproses', 'diambil', 'selesai', 'dibatalkan'])->default('menunggu_bayar');
            $table->decimal('total_bayar', 15, 2);
            $table->string('orderan_id', 100)->nullable(); // Untuk Midtrans: GALON-{id}-{timestamp}
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

            $table->foreign('id_galon_tipe')
                ->references('id_galon_tipe')
                ->on('galon_katalog')
                ->onDelete('cascade');

            // Indexes
            $table->index('id_penyewa');
            $table->index('id_kost');
            $table->index('status_galon');
            $table->index('orderan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_galon');
    }
};
