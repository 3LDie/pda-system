<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dentist_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');                  // Example: Juan dela Cruz
            $table->string('prc_no')->unique();           // Column 1: PRC No.
            $table->date('date_of_birth');                // Column 2: Date of Birth
            $table->text('home_address');                 // Column 3: Home Add
            $table->text('clinic_address');               // Column 4: Clinic Add
            $table->string('email_address')->unique();    // Column 5: e-mail add
            $table->string('contact_no');                 // Column 6: contact no.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dentist_profiles');
    }
};