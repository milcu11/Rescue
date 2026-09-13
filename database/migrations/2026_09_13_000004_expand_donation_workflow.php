<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('inventory_item_id')->nullable()->after('items_description')->constrained('inventory_items')->nullOnDelete();
            $table->unsignedInteger('inventory_quantity')->nullable()->after('inventory_item_id');
            $table->timestamp('inventory_linked_at')->nullable()->after('inventory_quantity');
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'received', 'verified', 'allocated', 'distributed'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'received', 'distributed'])->default('pending')->change();
            $table->dropConstrainedForeignId('inventory_item_id');
            $table->dropColumn(['inventory_quantity', 'inventory_linked_at']);
        });
    }
};
