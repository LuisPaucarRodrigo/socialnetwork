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
        Schema::create('payroll_monetary_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_detail_id')->constrained('payroll_details')->cascadeOnDelete();
            $table->foreignId('discount_param_id')->constrained('discount_params')->cascadeOnDelete();
            $table->float('amount')->default(0);
            $table->timestamps();

            $table->unique(['payroll_detail_id', 'discount_param_id'], 'pdmi_detail_param_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_monetary_discounts');
    }
};
