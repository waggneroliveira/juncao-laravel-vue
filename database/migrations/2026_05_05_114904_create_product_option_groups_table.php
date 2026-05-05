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
        Schema::create('product_option_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('combo_item_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('name'); // Ex: Escolha o tamanho
            $table->string('type'); // select, radio, checkbox

            $table->boolean('required')->default(false);
            $table->integer('max_selections')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_groups');
    }
};
