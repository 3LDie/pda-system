<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pda_memberships', function (Blueprint $table) {
            $table->id();
            // This line links this record to a specific dentist's ID
            $table->foreignId('dentist_profile_id')->constrained('dentist_profiles')->onDelete('cascade');
            
            $table->string('membership_year');            // Stores brackets like "1989-90", "1990-91"
            $table->string('status')->default('Active');  // e.g., Paid, Active, Pending
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pda_memberships');
    }
};