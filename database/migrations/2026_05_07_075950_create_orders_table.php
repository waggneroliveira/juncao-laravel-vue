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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, confirmed, preparing, ready, delivered, cancelled
            $table->string('delivery_method'); // delivery, pickup, local
            $table->string('payment_method'); // credit_card, debit_card, pix, cash
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('cashback', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();
            $table->string('estimated_time')->nullable(); // ex: "30 min"
            $table->dateTime('completed_at')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable(); // será relacionado depois
            $table->unsignedBigInteger('delivery_location_id')->nullable(); // será relacionado depois
            $table->json('delivery_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
