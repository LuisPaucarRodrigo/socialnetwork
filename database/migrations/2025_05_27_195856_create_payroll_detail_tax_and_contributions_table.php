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
        Schema::create('payroll_detail_tax_and_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_detail_id')->constrained('payroll_details')->cascadeOnDelete();
            $table->foreignId('t_a_c_param_id')->constrained('tax_and_contribution_params')->cascadeOnDelete();
            $table->float('amount')->default(0);
            $table->timestamps();

            $table->unique(['payroll_detail_id', 't_a_c_param_id'], 'pd_tcp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_detail_tax_and_contributions');
    }
};
