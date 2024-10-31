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
        Schema::create('cicsa_assignations', function (Blueprint $table) {
            $table->id();
            $table->date('assignation_date')->nullable();
            $table->string('project_name');
            $table->string('cost_center');
            $table->string('customer')->nullable();
            $table->string('project_code')->nullable();
            $table->string('cpe')->nullable();
            $table->string('zone');
            $table->date('project_deadline')->nullable();
            $table->string('manager');
            $table->string('user_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_assignations');
    }
};
