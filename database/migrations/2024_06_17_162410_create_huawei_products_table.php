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
        Schema::create('huawei_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hpl_id')->constrained('huawei_product_loads')->onDelete('cascade');
            $table->string('name');
            $table->string('anexe_name');
            $table->string('anexe_unit');
            $table->integer('quantity');
            $table->foreignId('pg1_id')->nullable()->constrained('price_guide1')->onDelete('cascade');
            $table->foreignId('pg2_id')->nullable()->constrained('price_guide2')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_products');
    }
};
