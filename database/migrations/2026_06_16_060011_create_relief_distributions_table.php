<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relief_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relief_operation_id')->constrained('relief_operations');
            $table->foreignId('evacuation_center_id')->constrained('evacuation_centers');
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->integer('quantity_distributed');
            $table->timestamp('distributed_at')->useCurrent();
            $table->integer('beneficiaries_count')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('distributed_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relief_distributions');
    }
};
