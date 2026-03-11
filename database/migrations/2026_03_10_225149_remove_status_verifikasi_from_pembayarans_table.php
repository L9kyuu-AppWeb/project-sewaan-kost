<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Remove status_verifikasi column as it's no longer used with Midtrans automatic verification.
     */
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn('status_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'proses_verifikasi', 'diverifikasi', 'ditolak'])
                  ->default('pending')
                  ->after('tanggal_bayar');
        });
    }
};
