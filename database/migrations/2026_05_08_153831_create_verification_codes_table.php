<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('whatsapp');
            $table->string('full_name');
            $table->string('code', 6);
            $table->string('token')->unique();
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['email', 'used']);
            $table->index('token');
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
};