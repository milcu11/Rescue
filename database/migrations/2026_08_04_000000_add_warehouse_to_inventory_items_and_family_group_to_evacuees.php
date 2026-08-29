<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'warehouse')) {
                $table->string('warehouse')->nullable()->after('status');
            }
        });

        Schema::table('evacuees', function (Blueprint $table) {
            if (!Schema::hasColumn('evacuees', 'family_group')) {
                $table->string('family_group')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_items', 'warehouse')) {
                $table->dropColumn('warehouse');
            }
        });

        Schema::table('evacuees', function (Blueprint $table) {
            if (Schema::hasColumn('evacuees', 'family_group')) {
                $table->dropColumn('family_group');
            }
        });
    }
};
