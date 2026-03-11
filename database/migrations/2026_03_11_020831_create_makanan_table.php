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
        Schema::create('makanan', function (Blueprint $table) {
            $table->id('id_makanan');
            $table->unsignedBigInteger('id_kost');
            $table->string('nama_makanan', 100);
            $table->decimal('harga', 15, 2);
            $table->integer('stok')->default(0);
            $table->boolean('is_available')->default(true);
            $table->string('foto_makanan', 255)->nullable();
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
        Schema::dropIfExists('makanan');
    }
};
