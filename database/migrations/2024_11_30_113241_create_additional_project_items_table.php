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
        Schema::create('additional_project_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('additional_project_id')->constrained('additional_projects')->cascadeOnDelete();
            $table->string('name');
            $table->float('amount');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_project_items');
    }
};
