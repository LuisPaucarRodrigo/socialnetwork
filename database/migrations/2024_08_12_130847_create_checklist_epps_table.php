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
        Schema::create('checklist_epps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('helmet');
            $table->string('chin_strap');
            $table->string('windbreaker');
            $table->string('vest');
            $table->string('safety_shoes');
            $table->string('tshirt_ls');
            $table->string('shirt_ls');
            $table->string('jeans');
            $table->string('coveralls');
            $table->string('jacket');
            $table->string('dark_glasses');
            $table->string('clear_glasses');
            $table->string('overglasses');
            $table->string('dust_mask');
            $table->string('earplugs');
            $table->string('latex_oil_gloves');
            $table->string('nitrile_oil_gloves');
            $table->string('split_leather_gloves');
            $table->string('precision_gloves');
            $table->string('cut_resistant_gloves');
            $table->string('double_lanyard');
            $table->string('harness');
            $table->string('positioning_lanyard');
            $table->string('carabiners');
            $table->string('ascenders');
            $table->string('sunscreen');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_epps');
    }
};
