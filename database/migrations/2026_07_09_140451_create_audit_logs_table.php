<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name'); // Caches name in case user profile gets wiped
            $table->string('action');    // e.g., 'REGISTER', 'RENEW', 'UPDATE', 'DELETE'
            $table->string('target_type'); // e.g., 'DentistProfile' or 'PdaMembership'
            $table->text('description'); // Descriptive text: "Registered Dr. Maria Clara (PRC: 0024689)"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};