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
        Schema::create('task_employees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('tasks_id')
                ->nullable()
                ->constrained('tasks')
                ->onDelete('cascade');
            $table->foreignId('project_employee_id')
                ->nullable()
                ->constrained('project_employee')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_employees');
    }
};
