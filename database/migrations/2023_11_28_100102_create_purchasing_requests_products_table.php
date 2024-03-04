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
        Schema::create('purchasing_requests_products', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->foreignId('purchasing_request_id')->constrained('purchasing_requests')->onDelete('cascade');
            $table->foreignId('purchase_product_id')->constrained('purchase_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasing_requests_products');
    }
};
