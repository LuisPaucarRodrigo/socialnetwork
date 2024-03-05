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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('state')->default('Pendiente');
            $table->foreignId('purchase_quote_id')->constrained()->onDelete('cascade');
            $table->string('facture_doc')->nullable();
            $table->date('facture_date')->nullable();
            $table->string('facture_number')->nullable();
            $table->string('remission_guide_doc')->nullable();
            $table->date('remission_guide_date')->nullable();
            $table->string('remission_guide_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
