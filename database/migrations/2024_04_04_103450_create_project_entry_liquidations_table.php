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
        Schema::create('project_entry_liquidations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_entry_id')->constrained();
            $table->integer('liquidated_quantity');
            $table->integer('refund_quantity');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_entry_liquidations');
    }
};
