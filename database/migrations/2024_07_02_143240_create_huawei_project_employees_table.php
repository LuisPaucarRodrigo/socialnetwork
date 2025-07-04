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
        Schema::create('huawei_project_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_project_schedule_id')->constrained('huawei_project_schedules')->onDelete('cascade');
            $table->string('employee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_project_employees');
    }
};
