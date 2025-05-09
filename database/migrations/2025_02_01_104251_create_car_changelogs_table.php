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
        Schema::create('car_changelogs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->double('mileage');
            $table->string('type');
            $table->string('invoice');
            $table->string('workshop');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('observation')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_changelogs');
    }
};
