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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('product_name');
            $table->decimal('base_price', 10, 2);
            $table->decimal('unit_price', 10, 2); // preço final unitário com customizações
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2); // unit_price * quantity
            $table->json('customizations')->nullable(); // tamanho, sabores, adicionais selecionados
            $table->json('combo_data')->nullable(); // dados do combo se for combo
            $table->boolean('is_combo')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
