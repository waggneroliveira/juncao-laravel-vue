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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // percentual ou valor fixo
            $table->decimal('value', 10, 2); // percentual (0-100) ou valor em reais
            $table->decimal('max_discount', 10, 2)->nullable(); // desconto máximo se percentual
            $table->decimal('min_order_value', 10, 2)->nullable(); // valor mínimo do pedido
            $table->integer('usage_limit')->nullable(); // limite de usos
            $table->integer('usage_count')->default(0); // vezes usado
            $table->integer('per_customer_limit')->default(1); // quantas vezes um cliente pode usar
            $table->boolean('is_active')->default(true);
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
