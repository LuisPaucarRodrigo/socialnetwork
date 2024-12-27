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
        Schema::create('document_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdivision_id')->nullable()->constrained('subdivisions')->nullOnDelete();
            $table->foreignId('document_id')->nullable()->unique()->constrained('documents')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->cascadeOnDelete();
            $table->foreignId('e_employee_id')->nullable()->constrained('external_employees')->cascadeOnDelete();
            $table->date('exp_date')->nullable();
            $table->string('state');
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->unique(['subdivision_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_registers');
    }
};
