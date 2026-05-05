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
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('option_group_id')
            ->constrained('product_option_groups')
            ->cascadeOnDelete();

            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);

            $table->boolean('is_default')->default(false);
            $table->integer('max_quantity')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
