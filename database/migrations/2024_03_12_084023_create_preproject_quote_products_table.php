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
        Schema::create('preproject_quote_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preproject_quote_id')->constrained('preproject_quotes')->onDelete('cascade');
            $table->foreignId('purchase_product_id')->constrained('purchase_products')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('unitary_price');
            $table->double('profit_margin');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preproject_quote_products');
    }
};
