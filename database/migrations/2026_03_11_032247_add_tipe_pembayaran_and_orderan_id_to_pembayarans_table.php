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
            // Add tipe_pembayaran column
            $table->enum('tipe_pembayaran', ['kamar', 'makanan', 'galon', 'laundry'])
                ->default('kamar')
                ->after('id_pesan');

            // Add orderan_id column (nullable, for flexible order reference)
            $table->string('orderan_id', 100)->nullable()->after('tipe_pembayaran');

            // Add index for faster lookups
            $table->index('tipe_pembayaran');
            $table->index('orderan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex(['tipe_pembayaran']);
            $table->dropIndex(['orderan_id']);
            $table->dropColumn(['tipe_pembayaran', 'orderan_id']);
        });
    }
};
