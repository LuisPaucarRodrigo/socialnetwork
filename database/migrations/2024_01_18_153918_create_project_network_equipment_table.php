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
        Schema::create('project_network_equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignId('network_equipment_id')
                ->nullable()
                ->constrained('network_equipment')
                ->onDelete('cascade');
            $table->text('observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_network_equipment');
    }
};
