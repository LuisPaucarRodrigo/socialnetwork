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
        Schema::create('huawei_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_entry_id')->constrained('huawei_entries')->onDelete('cascade');
            $table->foreignId('huawei_material_id')->nullable()->constrained('huawei_materials')->onDelete('cascade');
            $table->foreignId('huawei_equipment_serie_id')->nullable()->constrained('huawei_equipment_series')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_entry_details');
    }
};
