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
        Schema::create('output_project_product', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_product_id')
                ->nullable()
                ->constrained('project_product')
                ->onDelete('cascade');
            $table->integer('quantity');
            $table->text('observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_project_product');
    }
};
