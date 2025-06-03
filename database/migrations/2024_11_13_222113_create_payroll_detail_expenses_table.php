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
        Schema::create('payroll_detail_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->foreignId('payroll_detail_id')
                ->constrained('payroll_details')
                ->cascadeOnDelete();
            $table->foreignId('general_expense_id')
                ->constrained('general_expenses')
                ->cascadeOnDelete();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_detail_expenses');
    }
};
