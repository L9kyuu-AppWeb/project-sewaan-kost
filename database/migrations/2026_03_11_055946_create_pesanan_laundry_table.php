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
        Schema::create('pesanan_laundry', function (Blueprint $table) {
            $table->id('id_order_laundry');
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_kost');
            $table->unsignedBigInteger('id_laundry_tipe');
            $table->decimal('berat_kg', 5, 2)->nullable(); // Diisi owner setelah timbang
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->string('foto_awal', 255); // Foto pakaian dari penyewa
            $table->string('foto_selesai', 255)->nullable(); // Foto setelah selesai
            $table->date('tgl_selesai_estimasi')->nullable(); // Diisi owner setelah bayar
            $table->timestamp('tgl_selesai_aktual')->nullable(); // Auto saat upload foto_selesai
            $table->enum('status_laundry', ['menunggu_jemput', 'menunggu_bayar', 'sedang_dicuci', 'siap_antar', 'selesai', 'dibatalkan'])->default('menunggu_jemput');
            $table->string('orderan_id', 100)->nullable(); // Untuk Midtrans: LAUNDRY-{id}-{timestamp}
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

            $table->foreign('id_laundry_tipe')
                ->references('id_laundry_tipe')
                ->on('laundry_katalog')
                ->onDelete('cascade');

            // Indexes
            $table->index('id_penyewa');
            $table->index('id_kost');
            $table->index('status_laundry');
            $table->index('orderan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_laundry');
    }
};
