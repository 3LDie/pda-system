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
        Schema::table('pda_memberships', function (Blueprint $table) {
            // Adds the user_id column
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // Optional: If you want to enforce database-level relationship constraints:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pda_memberships', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};