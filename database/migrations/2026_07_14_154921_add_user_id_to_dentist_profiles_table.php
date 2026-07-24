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
            // Adds the user_id column after the 'id' column
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // Adds a foreign key constraint for data integrity
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dentist_profiles', function (Blueprint $table) {
            // Drop the foreign key constraint first, then the column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};