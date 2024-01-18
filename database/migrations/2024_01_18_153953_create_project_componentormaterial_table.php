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
        Schema::create('project_componentormaterial', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignId('component_or_material_id')
                ->nullable()
                ->constrained('component_or_materials')
                ->onDelete('cascade');
            $table->integer('quantity');
            $table->text('observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_componentormaterial');
    }
};
