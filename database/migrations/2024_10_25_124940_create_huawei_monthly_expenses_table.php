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
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->string('refund_status');
            $table->date('ec_expense_date')->nullable();
            $table->string('ec_op_number')->nullable();
            $table->double('ec_amount')->nullable();
            $table->foreignId('huawei_monthly_project_id')->constrained('huawei_monthly_projects')->onDelete('cascade');
            $table->foreignId('account_statement_id')->nullable()->constrained('account_statements')->onDelete('set null');
            $table->foreignId('general_expense_id')->constrained('general_expenses')->cascadeOnDelete();
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
