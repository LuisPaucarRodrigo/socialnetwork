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
        Schema::create('huawei_equipment_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_equipment_id')->constrained('huawei_equipments')->onDelete('cascade');
            $table->string('serie_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_equipment_series');
    }
};
