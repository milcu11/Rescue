<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', [
                'low_stock',
                'near_expiration',
                'center_full',
                'new_donation',
                'distribution_recorded',
                'operation_created',
                'operation_completed',
                'evacuee_checked_in',
                'general',
            ])->default('general')->change();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('type', [
                'low_stock',
                'center_full',
                'new_donation',
                'distribution_recorded',
                'operation_created',
                'operation_completed',
                'evacuee_checked_in',
                'general',
            ])->default('general')->change();
        });
    }
};
