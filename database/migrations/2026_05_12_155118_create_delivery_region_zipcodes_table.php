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
        Schema::create('delivery_region_zipcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_region_id')->constrained('delivery_regions')->onDelete('cascade');
            $table->string('zipcode_prefix', 9);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_region_zipcodes');
    }
};
