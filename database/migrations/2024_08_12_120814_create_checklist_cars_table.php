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
            $table->string('personal_2')->nullable();
            $table->string('zone');
            //CAR DOCS
            $table->string('plate');
            $table->string('circulation_doc');
            $table->string('technical_doc');
            $table->string('soat');
            //CAR STATES
            $table->string('horn');
            $table->string('brakes');
            $table->string('highlowlights');
            $table->string('flashing');
            $table->string('directional');
            $table->string('mirrors');
            $table->string('tires');
            $table->string('bumper');
            $table->string('temperature');
            $table->string('oil');
            $table->string('fuel');
            $table->string('cleanstate');
            $table->string('doors');
            $table->string('windshield');
            $table->string('engine');
            $table->string('battery');
            //CAR TOOLS
            $table->string('extinguisher');
            $table->string('medical_kit');
            $table->string('cones');
            $table->string('car_jack');
            $table->string('tire');
            $table->string('trailer_cable');
            $table->string('battery_cable');
            $table->string('reflective');
            $table->string('tool_kit');
            $table->string('alarm');
            $table->string('gps');
            $table->string('chocks');
            $table->string('internal_cab');
            $table->string('anti_roll_cab');
            $table->string('ladder_rack');
            $table->string('lateral_plate');
            //CAR PHOTOS
            $table->string('photo_left');
            $table->string('photo_right');
            $table->string('photo_back');
            $table->string('photo_front');
            $table->string('photo_first_tire');
            $table->string('photo_second_tire');
            $table->string('photo_third_tire');
            $table->string('photo_fourth_tire');

            $table->text('observations');
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
