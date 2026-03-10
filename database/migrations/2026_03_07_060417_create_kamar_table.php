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
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->unsignedBigInteger('id_kost');
            $table->string('nomor_kamar', 10);
            $table->integer('lantai')->nullable();
            $table->decimal('harga_per_bulan', 15, 2);
            $table->enum('status_kamar', ['tersedia', 'dipesan', 'terisi'])->default('tersedia');
            $table->string('ukuran_kamar', 20)->nullable();
            $table->text('fasilitas_kamar')->nullable();
            $table->string('foto_kamar', 255)->nullable();
            $table->timestamps();

            // Foreign key constraint with cascade delete
            $table->foreign('id_kost')
                ->references('id_kost')
                ->on('kost')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
