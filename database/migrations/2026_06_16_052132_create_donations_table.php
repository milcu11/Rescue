<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->string('donor_name');
            $table->string('donor_contact')->nullable();
            $table->string('donor_email')->nullable();
            $table->enum('type', ['in-kind', 'monetary']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('items_description')->nullable();
            $table->enum('status', ['pending', 'received', 'distributed'])->default('pending');
            $table->string('received_by')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
