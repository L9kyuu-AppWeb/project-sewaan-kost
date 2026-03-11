<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Remove manual payment fields since we're using Midtrans only.
     */
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Drop manual payment verification fields
            $table->dropColumn([
                'nama_bank',
                'nomor_rekening',
                'bukti_pembayaran',
                'catatan_verifikasi',
                'verified_by',
                'verified_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Restore manual payment verification fields
            $table->string('nama_bank', 50)->nullable()->after('jenis_pembayaran');
            $table->string('nomor_rekening', 50)->nullable()->after('nama_bank');
            $table->string('bukti_pembayaran')->nullable()->after('nomor_rekening');
            $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            $table->unsignedBigInteger('verified_by')->nullable()->after('catatan_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }
};
