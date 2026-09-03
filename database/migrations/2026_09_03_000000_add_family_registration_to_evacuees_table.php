<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evacuees', function (Blueprint $table) {
            if (!Schema::hasColumn('evacuees', 'family_qr_token')) {
                $table->string('family_qr_token', 64)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('evacuees', 'contact_phone')) {
                $table->string('contact_phone', 32)->nullable()->after('needs');
            }
            $table->unsignedBigInteger('recorded_by')->nullable()->change();
            $table->timestamp('checked_in_at')->nullable()->change();
            $table->string('status', 32)->default('registered')->change();
        });
    }

    public function down(): void
    {
        Schema::table('evacuees', function (Blueprint $table) {
            if (Schema::hasColumn('evacuees', 'family_qr_token')) {
                $table->dropColumn('family_qr_token');
            }
            if (Schema::hasColumn('evacuees', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
        });
    }
};
