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
        // Rebuild the medical_logs table to include all required fields
        // from your feature list. This is better than creating multiple migrations.
        Schema::create('medical_logs', function (Blueprint $table) {
            $table->id();
            // A medical log is related to a pet, not an appointment.
            // The pet_id is handled via the PetMedical pivot table.
            $table->date('visit_date');
            $table->string('vet_name')->nullable();
            $table->string('clinic_name')->nullable();
            $table->text('reason_for_visit')->nullable();
            $table->string('diagnosis');
            $table->text('treatment_prescribed');
            $table->text('notes')->nullable();
            $table->string('attachment_url', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the entire table if the migration is rolled back.
        Schema::dropIfExists('medical_logs');
    }
};
