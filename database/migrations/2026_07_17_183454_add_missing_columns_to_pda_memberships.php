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
            // Adding the missing payment_status column
            $table->string('payment_status')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pda_memberships', function (Blueprint $table) {
            // Drop the column if rolling back
            $table->dropColumn('payment_status');
        });
    }
};