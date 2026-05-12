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
        Schema::table('client_addresses', function (Blueprint $table) {
            $table->foreignId('delivery_region_id')->nullable()->constrained('delivery_regions')->nullOnDelete();
            $table->index('delivery_region_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_addresses', function (Blueprint $table) {
            $table->dropForeign(['delivery_region_id']);
            $table->dropColumn('delivery_region_id');
        });
    }
};
