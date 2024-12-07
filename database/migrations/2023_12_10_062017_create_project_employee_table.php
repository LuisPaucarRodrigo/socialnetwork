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
        Schema::create('project_employee', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('salary_per_day');
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->onDelete('set null');
            $table->string('charge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_employee');
    }
};
