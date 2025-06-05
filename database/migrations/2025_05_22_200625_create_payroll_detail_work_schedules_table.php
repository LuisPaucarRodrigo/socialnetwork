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
        Schema::create('payroll_detail_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_detail_id')->unique()->constrained('payroll_details')->cascadeOnDelete();
            $table->text('subsidized_days');
            $table->text('non_subsidized_days');
            $table->string('regular_hours');
            $table->string('overtime_hours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_detail_work_schedules');
    }
};
