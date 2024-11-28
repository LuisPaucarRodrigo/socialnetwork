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
        Schema::create('cost_line_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_entry_id')->constrained('resource_entries')->cascadeOnDelete();
            $table->foreignId('cost_line_id')->constrained('cost_lines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_line_resources');
    }
};
