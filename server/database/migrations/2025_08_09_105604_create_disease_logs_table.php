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
        Schema::create('disease_logs', function (Blueprint $table) {
            $table->id('disease_id');
            $table->text('symptoms');
            $table->text('causes');
            $table->text('treat_options');
            $table->string('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_logs');
    }
};
