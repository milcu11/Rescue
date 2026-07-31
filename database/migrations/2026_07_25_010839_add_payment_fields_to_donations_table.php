<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('paymongo_checkout_id')->nullable()->after('type');
            $table->string('paymongo_payment_id')->nullable()->after('paymongo_checkout_id');
            $table->enum('payment_status', ['not_required', 'unpaid', 'paid', 'failed'])->default('unpaid')->after('paymongo_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'paymongo_checkout_id',
                'paymongo_payment_id',
                'payment_status',
            ]);
        });
    }
};
