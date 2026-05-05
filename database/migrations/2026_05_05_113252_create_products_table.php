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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->integer('cashback')->default(0);

            $table->string('product_type'); // food, combo, beverage
            $table->string('cuisine_type')->nullable();

            $table->boolean('is_combo')->default(false);
            $table->boolean('featured')->default(false);

            $table->integer('order')->default(0);

            $table->json('tags')->nullable(); // simples e eficiente
            $table->json('specifications')->nullable(); // calories, etc

            $table->string('path_image')->nullable();
            $table->boolean('active')->default(0);
            $table->integer('sorting')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
