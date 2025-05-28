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
        Schema::create('payroll_detail_monetary_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_detail_id')->constrained('payroll_details')->cascadeOnDelete();
            $table->foreignId('income_param_id')->constrained('income_params')->cascadeOnDelete();
            $table->float('accrued_amount')->default(0);
            $table->float('paid_amount')->default(0);
            $table->timestamps();

            $table->unique(['payroll_detail_id', 'income_param_id'], 'pdmi_detail_param_unique');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_detail_monetary_incomes');
    }
};
