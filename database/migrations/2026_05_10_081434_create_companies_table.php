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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();

            $table->text('description')->nullable();

            $table->string('path_image')->nullable();
            $table->string('path_image_banner')->nullable();

            $table->string('timezone')->default('America/Sao_Paulo');

            /*
            Controle manual opcional:
            force_open  => força aberto
            force_closed => força fechado
            */
            $table->enum('operation_mode', [
                'automatic',
                'force_open',
                'force_closed'
            ])->default('automatic');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
