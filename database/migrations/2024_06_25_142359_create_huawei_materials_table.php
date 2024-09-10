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
        Schema::create('huawei_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('claro_code')->nullable();
            $table->string('unit');
            $table->foreignId('model_id')->nullable()->constrained('brand_models')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_materials');
    }
};
