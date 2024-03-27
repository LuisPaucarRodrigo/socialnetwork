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
        Schema::create('preproject_quote_items', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('unit');
            $table->string('days');
            $table->integer('quantity');
            $table->double('unit_price');
            $table->double('profit_margin');
            $table->foreignId('preproject_quote_id')->constrained('preproject_quotes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preproject_quote_items');
    }
};
