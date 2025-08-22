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
        Schema::create('medical_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('appointments')->onDelete('cascade'); // Foreign key to 'apps' table
            $table->string('treat_pres');
            $table->string('diagnosis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_logs');
    }
};

