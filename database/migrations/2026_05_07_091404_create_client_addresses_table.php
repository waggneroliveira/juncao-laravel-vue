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
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            // Campos do formulário
            $table->string('nickname');              // Apelido (Casa, Trabalho...)
            $table->string('cep', 9);                // CEP (formato 00000-000)
            $table->string('street');                // Rua
            $table->string('number', 20);            // Número
            $table->string('complement')->nullable(); // Complemento (Apto, Bloco...)
            $table->string('neighborhood');          // Bairro
            $table->string('city');                  // Cidade
            $table->string('state', 2);              // Estado (UF)
            $table->string('reference')->nullable(); // Ponto de referência (seu campo chama 'reference')
            $table->text('instructions')->nullable(); // Instruções de entrega
            
            $table->boolean('primary')->default(0);  // Endereço principal
            $table->boolean('active')->default(1);   // Para soft delete manual
            
            $table->timestamps();
            
            // Índices para buscas
            $table->index('client_id');
            $table->index('primary');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_addresses');
    }
};
