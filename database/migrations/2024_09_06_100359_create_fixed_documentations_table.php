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
        Schema::create('fixed_documentations', function (Blueprint $table) {
            $table->id();
            $table->string('employees');
            $table->string('dni');
            $table->string('driver_license');
            $table->string('registration_form');
            
            $table->string('home_verification');
            $table->string('vericom_data_authorization');
            $table->string('dj_pension_system');
            $table->string('electricity_water_receipt');

            $table->string('curriculum');
            $table->string('digital_photo');
            $table->string('signature');
            $table->string('vaccination_card');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_documentations');
    }
};
