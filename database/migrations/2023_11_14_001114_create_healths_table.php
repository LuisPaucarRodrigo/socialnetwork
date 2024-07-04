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
            $table->string('blood_group')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('shoe_size')->nullable();
            $table->string('shirt_size')->nullable();
            $table->string('pants_size')->nullable();
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
