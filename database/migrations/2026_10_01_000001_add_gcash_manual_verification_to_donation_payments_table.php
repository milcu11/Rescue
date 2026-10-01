<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->string('gcash_reference_number')->nullable()->unique()->after('payment_method');
            $table->string('proof_image_path')->nullable()->after('gcash_reference_number');
            $table->foreignId('verified_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->string('rejection_reason')->nullable()->after('verified_at');
        });

        // Widen status/payment_status to plain strings (via doctrine/dbal) so new manual
        // GCash verification states aren't constrained by the original MySQL enum list.
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->change();
        });
    }

    public function down(): void
    {
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['gcash_reference_number', 'proof_image_path', 'verified_at', 'rejection_reason']);
        });
    }
};

