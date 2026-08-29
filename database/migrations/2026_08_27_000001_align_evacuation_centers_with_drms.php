<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing fields for DRMS alignment
        Schema::table('evacuation_centers', function (Blueprint $table) {
            // Add intake_procedures and required_items fields
            if (!Schema::hasColumn('evacuation_centers', 'intake_procedures')) {
                $table->text('intake_procedures')->nullable();
            }
            if (!Schema::hasColumn('evacuation_centers', 'required_items')) {
                $table->text('required_items')->nullable();
            }
        });

        // Rename contact_number to contact_phone
        if (Schema::hasColumn('evacuation_centers', 'contact_number')) {
            Schema::table('evacuation_centers', function (Blueprint $table) {
                $table->renameColumn('contact_number', 'contact_phone');
            });
        }
    }

    public function down(): void
    {
        Schema::table('evacuation_centers', function (Blueprint $table) {
            if (Schema::hasColumn('evacuation_centers', 'contact_phone')) {
                $table->renameColumn('contact_phone', 'contact_number');
            }
            if (Schema::hasColumn('evacuation_centers', 'intake_procedures')) {
                $table->dropColumn('intake_procedures');
            }
            if (Schema::hasColumn('evacuation_centers', 'required_items')) {
                $table->dropColumn('required_items');
            }
        });
    }
};
