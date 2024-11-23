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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('basic_salary');
            $table->float('life_ley');
            $table->string('state')->default('Active');
            $table->boolean('discount_remuneration');
            $table->boolean('discount_sctr');
            $table->integer('days_taken')->default(0);  
            $table->date('hire_date');
            $table->date('fired_date')->nullable();
            $table->string('expense_line');
            $table->boolean('state_travel_expenses')->default(false);
            $table->string('type_contract');
            $table->float('amount_travel_expenses');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('pension_id')->constrained('pensions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
