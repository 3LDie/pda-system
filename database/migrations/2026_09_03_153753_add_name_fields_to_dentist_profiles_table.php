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
        Schema::table('dentist_profiles', function (Blueprint $table) {
            $table->string('last_name')->after('id')->nullable();
            $table->string('first_name')->after('last_name')->nullable();
            $table->string('middle_name')->after('first_name')->nullable();
            $table->string('extension')->after('middle_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dentist_profiles', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'first_name', 'middle_name', 'extension']);
        });
    }
};