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
        Schema::create('pesan', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->unsignedBigInteger('id_penyewa');
            $table->unsignedBigInteger('id_kamar');
            $table->datetime('tgl_pemesanan');
            $table->date('tgl_mulai');
            $table->integer('durasi_bulan');
            $table->date('tgl_selesai');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status_pesan', [
                'menunggu_pembayaran',
                'proses_verifikasi',
                'aktif',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_pembayaran');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key constraints with cascade delete
            $table->foreign('id_penyewa')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_kamar')
                ->references('id_kamar')
                ->on('kamar')
                ->onDelete('cascade');

            // Indexes for performance
            $table->index('status_pesan');
            $table->index('tgl_pemesanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};
