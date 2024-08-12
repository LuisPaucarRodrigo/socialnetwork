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
        Schema::create('checklist_cars', function (Blueprint $table) {
            $table->id();
            $table->text('reason');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('additionalEmployees')->nullable();
            $table->string('zone');
            
            $table->string('plate');
            $table->string('circulation');
            $table->string('technique');
            $table->string('soat');
            $table->string('hornState');
            $table->string('brakesState');
            $table->string('headlightsState');
            $table->string('indicatorsState');
            $table->string('mirrorsState');
            $table->string('tiresState');
            $table->string('bumpersState');
            $table->string('temperatureGauge');
            $table->string('oilGauge');
            $table->string('fuelGauge');
            $table->string('vehicleCleanliness');
            $table->string('doorsState');
            $table->string('windshieldState');
            $table->string('engineState');
            $table->string('batteryState');
            $table->string('extinguisher');
            $table->string('firstAidKit');
            $table->string('cones');
            $table->string('jack');
            $table->string('spareTire');
            $table->string('towCable');
            $table->string('batteryCable');
            $table->string('reflector');
            $table->string('emergencyKit');
            $table->string('alarm');
            $table->string('gps');
            $table->string('chocks');
            $table->string('interiorLight');
            $table->string('rolloverProtection');
            $table->string('ladderHolder');
            $table->string('sidePlate');
            $table->string('front');
            $table->string('leftSide');
            $table->string('rightSide');
            $table->string('interior');
            $table->string('rearLeftTire');
            $table->string('rearRightTire');
            $table->string('frontRightTire');
            $table->string('frontLeftTire');
            

            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_cars');
    }
};
