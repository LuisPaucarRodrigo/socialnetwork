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
        Schema::create('healths', function (Blueprint $table) {
            $table->id();
            $table->string('blood_group');
            $table->string('weight');
            $table->string('height');
            $table->string('shoe_size');
            $table->string('shirt_size');
            $table->string('pants_size');
            $table->string('medical_condition');
            $table->string('allergies');
            $table->string('operations');
            $table->string('accidents');
            $table->string('vaccinations');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healths');
    }
};
