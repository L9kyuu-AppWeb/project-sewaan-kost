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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_pesan');
            $table->enum('jenis_pembayaran', ['transfer_bank', 'ewallet', 'tunai'])->default('transfer_bank');
            $table->string('nama_bank', 50)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->enum('status_verifikasi', ['pending', 'diverifikasi', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable(); // id_pemilik who verified
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_pesan')
                ->references('id_pesan')
                ->on('pesan')
                ->onDelete('cascade');

            $table->index('status_verifikasi');
            $table->index('tanggal_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
