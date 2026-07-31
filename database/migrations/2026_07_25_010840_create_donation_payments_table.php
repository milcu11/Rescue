<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations');
            $table->string('paymongo_checkout_id')->nullable();
            $table->string('paymongo_payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('checkout_url')->nullable();
            $table->json('paymongo_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_payments');
    }
};
