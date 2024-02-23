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
        Schema::create('resource_historial', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('quantity');
            $table->text('observation')->nullable();
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignId('resource_id')
                ->nullable()
                ->constrained('resources')
                ->onDelete('set null');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_historial');
    }
};
