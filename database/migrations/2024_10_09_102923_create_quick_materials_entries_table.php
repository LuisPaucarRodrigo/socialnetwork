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
        Schema::create('quick_materials_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date')->nullable();
            $table->double('quantity')->nullable();
            $table->double('unit_price')->nullable();
            $table->string('employee')->nullable();
            $table->text('observation')->nullable();
            $table->foreignId('quick_material_id')->constrained('quick_materials')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_materials_entries');
    }
};
