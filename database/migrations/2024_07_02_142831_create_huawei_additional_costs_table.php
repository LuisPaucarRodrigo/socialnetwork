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
        Schema::create('huawei_additional_costs', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
            $table->string('ruc')->unique();
            $table->string('zone');
            $table->string('type_doc');
            $table->string('doc_number')->unique();
            $table->date('doc_date');
            $table->double('amount');
            $table->string('description')->nullable();
            $table->foreignId('huawei_project_id')->constrained('huawei_projects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_additional_costs');
    }
};
