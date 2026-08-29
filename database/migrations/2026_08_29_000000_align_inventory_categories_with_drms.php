<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const CATEGORIES = [
        'food',
        'medical',
        'clothing',
        'tools',
        'other',
        'emergency',
        'first_aid',
        'hygiene',
        'water',
    ];

    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->enum('category', [
                'food',
                'medicine',
                'medical',
                'clothing',
                'tools',
                'other',
            ])->change();
        });

        DB::table('inventory_items')
            ->where('category', 'medicine')
            ->update(['category' => 'medical']);

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->enum('category', self::CATEGORIES)->change();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->enum('category', [...self::CATEGORIES, 'medicine'])->change();
        });

        DB::table('inventory_items')
            ->where('category', 'medical')
            ->update(['category' => 'medicine']);

        DB::table('inventory_items')
            ->whereIn('category', ['emergency', 'first_aid', 'hygiene', 'water'])
            ->update(['category' => 'other']);

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->enum('category', ['food', 'medicine', 'clothing', 'tools', 'other'])->change();
        });
    }
};