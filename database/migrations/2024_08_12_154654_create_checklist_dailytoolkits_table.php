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
        Schema::create('checklist_dailytoolkits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name');
            $table->string('personal_2')->nullable();
            $table->string('zone');

            $table->string('power_meter');
            $table->string('ammeter_clamp');
            $table->string('cutting_pliers');
            $table->string('nose_pliers');
            $table->string('universal_pliers');
            $table->string('tape');
            $table->string('cutter');
            $table->string('knob_driver');
            $table->string('screwdriver_set');
            $table->string('allenkeys_set');
            $table->string('thor_screwboard');
            $table->string('helmet_flashlight');
            $table->string('freanch_key');
            $table->string('pyrometer');
            $table->string('laptop');
            $table->string('console_cables');
            $table->string('network_adapter');


            $table->text('observations')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_dailytoolkits');
    }
};
