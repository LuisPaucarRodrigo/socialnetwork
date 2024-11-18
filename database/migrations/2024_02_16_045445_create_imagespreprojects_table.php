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
        Schema::create('imagespreprojects', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('observation')->nullable();
            $table->boolean('state')->nullable();
            $table->string('image')->unique();
            $table->string('lat');
            $table->string('lon');
            $table->foreignId('preproject_code_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagespreprojects');
    }
};
