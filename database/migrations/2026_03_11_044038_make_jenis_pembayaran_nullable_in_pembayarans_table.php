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
            // Make jenis_pembayaran nullable since it will be updated by Midtrans callback
            $table->enum('jenis_pembayaran', ['transfer_bank', 'ewallet', 'tunai'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Revert to not nullable with default
            $table->enum('jenis_pembayaran', ['transfer_bank', 'ewallet', 'tunai'])->default('transfer_bank')->change();
        });
    }
};
