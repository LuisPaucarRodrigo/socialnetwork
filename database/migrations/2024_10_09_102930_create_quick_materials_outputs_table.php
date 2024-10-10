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
        Schema::create('quick_materials_outputs', function (Blueprint $table) {
            $table->id();
            $table->date('output_date')->nullable();
            $table->double('quantity')->nullable();
            $table->foreignId('quick_material_entry_id')->constrained('quick_materials_entries')->onDelete('cascade');
            $table->string('employee')->nullable();
            $table->foreignId('huawei_project_id')->nullable()->constrained('huawei_projects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_materials_outputs');
    }
};
