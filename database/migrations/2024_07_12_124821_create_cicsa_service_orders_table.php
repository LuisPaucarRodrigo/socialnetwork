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
        Schema::create('cicsa_service_orders', function (Blueprint $table) {
            $table->id();
            $table->date('service_order_date')->nullable();
            $table->string('service_order')->default('Pendiente');
            $table->string('estimate_sheet')->default('Pendiente');
            $table->string('purchase_order')->default('Pendiente');
            $table->string('pdf_invoice')->default('Pendiente');
            $table->string('zip_invoice')->default('Pendiente');
            $table->string('document');
            $table->string('document_invoice')->nullable();
            $table->string('user_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cicsa_assignation_id')->nullable()->constrained('cicsa_assignations')->onDelete('cascade');
            $table->foreignId('cicsa_purchase_order_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_service_orders');
    }
};
