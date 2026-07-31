<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evacuees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evacuation_center_id')->constrained('evacuation_centers');
            $table->string('name');
            $table->integer('family_members')->default(1);
            $table->string('barangay_origin')->nullable();
            $table->text('needs')->nullable();
            $table->string('id_presented')->nullable();
            $table->enum('status', ['checked_in', 'checked_out'])->default('checked_in');
            $table->timestamp('checked_in_at')->useCurrent();
            $table->timestamp('checked_out_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evacuees');
    }
};
