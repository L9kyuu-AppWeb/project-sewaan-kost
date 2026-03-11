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
        Schema::create('galon_katalog', function (Blueprint $table) {
            $table->id('id_galon_tipe');
            $table->unsignedBigInteger('id_kost');
            $table->string('nama_air', 100);
            $table->decimal('harga', 15, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_kost')
                ->references('id_kost')
                ->on('kost')
                ->onDelete('cascade');

            // Indexes
            $table->index('id_kost');
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galon_katalog');
    }
};
