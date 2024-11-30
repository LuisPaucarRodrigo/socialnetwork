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
            $table->string('operation_number')->nullable();
            $table->date('operation_date')->nullable();
            $table->string('type');
            $table->foreignId('payroll_detail_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_statement_id')->nullable()
                ->constrained('account_statements')
                ->onDelete('set null');
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
