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
        Schema::create('preproject_codes', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->foreignId('preproject_title_id')->constrained()->onDelete('cascade');
            $table->foreignId('code_id')->constrained()->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preproject_codes');
    }
};
