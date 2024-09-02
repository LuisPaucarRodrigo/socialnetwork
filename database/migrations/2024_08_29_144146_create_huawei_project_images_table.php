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
        Schema::create('huawei_project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_project_code_id')->constrained('huawei_project_codes')->onDelete('cascade');
            $table->string('image');
            $table->string('description')->nullable();
            $table->string('observation')->nullable();
            $table->string('lat');
            $table->string('lon');
            $table->string('site');
            $table->boolean('state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_project_images');
    }
};
