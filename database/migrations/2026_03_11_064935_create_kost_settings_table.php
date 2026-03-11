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
        Schema::create('kost_settings', function (Blueprint $table) {
            $table->id('id_setting');
            $table->unsignedBigInteger('id_kost')->unique();
            
            // Feature flags - default disabled
            $table->boolean('enable_makanan')->default(false);
            $table->boolean('enable_galon')->default(false);
            $table->boolean('enable_laundry')->default(false);
            
            $table->timestamps();

            // Foreign key constraint
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
        Schema::dropIfExists('kost_settings');
    }
};
