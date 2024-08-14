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
        Schema::create('checklist_toolkits', function (Blueprint $table) {
            $table->id();
            $table->text('reason');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('additionalEmployees')->nullable();
            $table->string('zone');
            //KIT TOOLS
            $table->string('carabiner');
            $table->string('wireStripper');
            $table->string('crimper');
            $table->string('terminalCrimper');
            $table->string('files');
            $table->string('allenKeys');
            $table->string('readlineKit');
            $table->string('impactWrench');
            $table->string('dielectricTools');
            $table->string('cuttingTools');
            $table->string('forceps');
            $table->string('straightWrench');
            $table->string('frenchWrench');
            $table->string('saw');
            $table->string('silicone');
            $table->string('pulley');
            $table->string('tapeMeasure');
            $table->string('sling');
            $table->string('kit');
            $table->string('drillBits');
            $table->string('punch');
            $table->string('extractor');
            $table->string('wrenchSet');
            $table->string('cutter');
            $table->string('hammer');
            $table->string('largeToolBag');
            $table->string('mediumToolBag');
            $table->string('fallProtectionCar');
            $table->string('lifeline');
            $table->string('positioningLanyard');
            $table->string('harness');
            $table->string('pressureWasher');
            $table->string('blower');
            $table->string('megommeter');
            $table->string('earthTester');
            $table->string('perimeterMeter');
            $table->string('manometer');
            $table->string('pyrometer');
            $table->string('laptop');
            $table->string('drill');
            $table->string('compass');
            $table->string('inclinometer');
            $table->string('flashlight');
            $table->string('powerMeter');
            $table->string('glueGun');
            $table->string('solderingGun');
            $table->string('stepLadder');
            $table->string('sprayer');
            $table->string('rj45Connector');
            $table->string('networkConsole');
            $table->string('networkAdapter');
            $table->string('hotStick');
            $table->string('rope75');
            $table->string('ladder');
            $table->string('extensionCord');
            $table->string('longCable');
            $table->string('padlock');
            $table->string('chains');
            $table->string('hose');
            $table->string('corporatePhone');
            
            $table->text('observation')->nullable();
            $table->string('badTools')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_toolkits');
    }
};
