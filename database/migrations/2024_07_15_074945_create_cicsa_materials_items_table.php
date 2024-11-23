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
        Schema::create('cicsa_materials_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cicsa_material_id')->constrained('cicsa_materials')->onDelete('cascade');
            $table->string('code_ax');
            $table->string('name');
            $table->string('unit');
            $table->string('type');
            $table->integer('total_quantity')->nullable();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_materials_items');
    }
};
