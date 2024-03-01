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
        Schema::create('purchase_quotes_products', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->double('amount', 10, 2);
            $table->foreignId('purchase_quote_id')->constrained('purchase_quotes')->onDelete('cascade');
            $table->foreignId('purchase_product_id')->constrained('purchase_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_quotes_products');
    }
};
