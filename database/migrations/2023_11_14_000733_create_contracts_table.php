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
            $table->decimal('basic_salary', 10, 2);
            $table->string('state')->default('Active');
            $table->integer('days_taken')->default(0);
            $table->date('hire_date');
            $table->date('fired_date')->nullable();
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
