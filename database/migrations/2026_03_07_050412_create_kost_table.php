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
        Schema::create('kost', function (Blueprint $table) {
            $table->id('id_kost');
            $table->unsignedBigInteger('id_pemilik');
            $table->string('nama_kost', 100);
            $table->text('alamat');
            $table->text('deskripsi')->nullable();
            $table->text('fasilitas_umum')->nullable();
            $table->text('peraturan')->nullable();
            $table->string('foto_kost', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();

            // Foreign key constraint with cascade delete
            $table->foreign('id_pemilik')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kost');
    }
};
