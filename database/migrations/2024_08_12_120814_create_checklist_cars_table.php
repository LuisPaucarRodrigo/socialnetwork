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
            $table->foreignId('car_id')->nullable()->constrained('cars')->onDelete('set null');
            $table->string('user_name');
            $table->string('additionalEmployees')->nullable();
            $table->string('zone');
            // $table->integer('km');
            
            $table->string('plate');
            // $table->string('circulation');
            // $table->string('technique');
            // $table->string('soat');
            $table->string('hornState');
            $table->string('brakesState');
            $table->string('headlightsState');
            $table->string('intermitentlightState');
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
            $table->string('chocks');
            $table->string('ladderHolder');
            $table->string('sidePlate');
            $table->string('front');
            $table->string('leftSide');
            $table->string('rightSide');
            $table->string(column: 'interior');
            $table->string('rearLeftTire');
            $table->string('rearRightTire');
            $table->string('frontRightTire');
            $table->string('frontLeftTire');
            
            $table->string('beak');
            $table->string('shovel');
            $table->string('gps');
            $table->string('rollCage');
            $table->string('fogLights');
            $table->string('protectionCage');
            $table->string('hoopInsurance');
            $table->string(column: 'headlightInsurance');
            $table->string(column: 'cardProtector');
            $table->string(column: 'safetyTriangle');
            $table->string(column: 'wheelWrench');
            $table->string(column: 'back');
            $table->string(column: 'dashboard');
            $table->string(column: 'rearSeat');

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
