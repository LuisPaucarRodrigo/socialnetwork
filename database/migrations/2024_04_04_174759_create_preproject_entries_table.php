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
        Schema::create('preproject_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preproject_id')->constrained();
            $table->foreignId('entry_id')->nullable()->constrained();
            $table->integer('quantity');
            $table->text('observation')->nullable();
            $table->decimal('unitary_price')->nullable();
            $table->boolean('state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preproject_entries');
    }
};
