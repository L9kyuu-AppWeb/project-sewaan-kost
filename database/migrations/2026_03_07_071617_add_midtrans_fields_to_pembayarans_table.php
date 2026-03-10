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
            // Midtrans integration fields
            $table->string('order_id', 50)->nullable()->unique()->after('id_pesan');
            $table->string('transaction_id', 100)->nullable()->after('order_id');
            $table->string('payment_type', 50)->nullable()->after('transaction_id');
            $table->string('transaction_status', 20)->default('pending')->after('payment_type');
            $table->string('snap_token', 255)->nullable()->after('transaction_status');
            $table->timestamp('transaction_time')->nullable()->after('snap_token');
            $table->timestamp('settlement_time')->nullable()->after('transaction_time');
            $table->timestamp('expire_time')->nullable()->after('settlement_time');
            
            // Indexes for performance
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['transaction_id']);
            $table->dropIndex(['transaction_status']);
            
            $table->dropColumn([
                'order_id',
                'transaction_id',
                'payment_type',
                'transaction_status',
                'snap_token',
                'transaction_time',
                'settlement_time',
                'expire_time',
            ]);
        });
    }
};
