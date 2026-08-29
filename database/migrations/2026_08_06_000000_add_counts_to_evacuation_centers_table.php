<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evacuation_centers', function (Blueprint $table) {
            $table->integer('families_registered')->default(0)->after('current_occupancy');
            $table->integer('medical_needs_count')->default(0)->after('families_registered');
        });
    }

    public function down(): void
    {
        Schema::table('evacuation_centers', function (Blueprint $table) {
            $table->dropColumn(['families_registered', 'medical_needs_count']);
        });
    }
};
