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
        Schema::table('pembayarans', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign('pembayarans_id_pesan_foreign');
            
            // Make id_pesan nullable
            $table->unsignedBigInteger('id_pesan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Revert id_pesan to not nullable
            $table->unsignedBigInteger('id_pesan')->nullable(false)->change();
            
            // Re-add foreign key constraint (this may fail if there are orphaned records)
            $table->foreign('id_pesan')
                ->references('id_pesan')
                ->on('pesan')
                ->onDelete('cascade');
        });
    }
};
