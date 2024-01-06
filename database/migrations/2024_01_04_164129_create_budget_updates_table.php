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
        Schema::create('budget_updates', function (Blueprint $table) {
            $table->id();
            $table->double('new_budget', 10, 2);
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->text('reason');
            $table->date('update_date');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('approved_update_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_updates');
    }
};
