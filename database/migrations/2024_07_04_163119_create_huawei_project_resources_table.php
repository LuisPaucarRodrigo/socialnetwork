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
        Schema::create('huawei_project_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_project_id')->constrained('huawei_projects')->onDelete('cascade');
            $table->foreignId('huawei_entry_detail_id')->constrained('huawei_entry_details')->onDelete('cascade');
            $table->date('output_date');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_project_resources');
    }
};
