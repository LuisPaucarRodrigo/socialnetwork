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
        Schema::create('huawei_project_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->integer('days');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('huawei_project_id')->constrained('huawei_projects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_project_schedules');
    }
};
