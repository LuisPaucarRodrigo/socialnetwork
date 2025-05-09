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
        Schema::create('cicsa_feasibility_materials', function (Blueprint $table) {
            $table->id();
            $table->string('code_ax');
            $table->string('name');
            $table->string('unit');
            $table->integer('quantity');
            $table->string('type')->default('Pext');
            $table->foreignId('cicsa_feasibility_id')->constrained('cicsa_feasibilities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_feasibility_materials');
    }
};
