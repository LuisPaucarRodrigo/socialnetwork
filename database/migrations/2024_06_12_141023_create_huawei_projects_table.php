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
        Schema::create('huawei_projects', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('codsap')->nullable();
            $table->string('description')->nullable();
            $table->string('serie')->nullable();
            $table->string('period')->nullable();
            $table->string('hire')->nullable();
            $table->string('oc_pap')->nullable();
            $table->string('sites')->nullable();
            $table->string('general_status')->nullable();
            $table->string('status')->nullable();
            $table->double('monetary_value')->nullable();
            $table->string('observation')->nullable();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_projects');
    }
};
