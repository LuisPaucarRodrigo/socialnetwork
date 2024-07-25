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
        Schema::create('huawei_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('huawei_site_id')->constrained('huawei_sites')->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('ot');
            $table->string('pre_report')->nullable();
            $table->double('initial_amount')->nullable();
            $table->string('assigned_diu');
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_projects');
    }
};
