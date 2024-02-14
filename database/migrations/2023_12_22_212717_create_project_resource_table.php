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
        Schema::create('project_resource', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignId('resource_id')
                ->nullable()
                ->constrained('resources')
                ->onDelete('cascade');
            $table->integer('quantity');
            $table->double('unit_price')->nullable();
            $table->text('observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_resource');
    }
};
