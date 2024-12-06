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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained();
            $table->foreignId('employee_id')->constrained();
            $table->float('basic_salary');
            $table->float('amount_travel_expenses')->nullable();
            $table->float('life_ley');
            $table->string('state')->default('Active');
            $table->float('discount')->default(0.00);
            $table->boolean('discount_remuneration');
            $table->boolean('discount_sctr');
            $table->integer('days_taken')->default(0);
            $table->date('hire_date');
            $table->date('fired_date')->nullable();
            $table->foreignId('pension_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
