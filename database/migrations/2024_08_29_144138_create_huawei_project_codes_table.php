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
        Schema::create('huawei_project_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_project_stage_id')->constrained('huawei_project_stages')->onDelete('cascade');
            $table->foreignId('huawei_code_id')->constrained('huawei_codes')->onDelete('cascade');
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_project_codes');
    }
};
