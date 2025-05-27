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
        Schema::create('huawei_monthly_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
            $table->string('employee');
            $table->string('zone')->nullable();
            $table->date('expense_date');
            $table->string('cdp_type')->nullable();
            $table->string('doc_number')->nullable();
            $table->string('op_number')->nullable();
            $table->string('ruc')->nullable();
            $table->string('description');
            $table->double('amount');
            $table->string('image')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->date('ec_expense_date')->nullable();
            $table->string('ec_op_number')->nullable();
            $table->double('ec_amount')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('huawei_project_id')->nullable()->constrained('huawei_projects')->onDelete('set null');
            $table->foreignId('account_statement_id')->nullable()->constrained('account_statements')->onDelete('set null');
            $table->foreignId('general_expense_id')->constrained('general_expenses')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_monthly_expenses');
    }
};
