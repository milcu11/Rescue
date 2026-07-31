<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evacuation_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barangay');
            $table->string('address');
            $table->integer('capacity');
            $table->integer('current_occupancy')->default(0);
            $table->enum('status', ['active', 'full', 'closed'])->default('active');
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evacuation_centers');
    }
};
