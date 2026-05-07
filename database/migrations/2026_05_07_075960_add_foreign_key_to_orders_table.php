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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->nullOnDelete();
                
            $table->foreign('delivery_location_id')
                ->references('id')
                ->on('delivery_locations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropForeign(['delivery_location_id']);
        });
    }
};
