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
            $table->date('service_order_date');
            $table->string('service_order');
            $table->string('estimate_sheet');
            $table->string('purchase_order');
            $table->string('pdf_invoice');
            $table->string('zip_invoice');
            $table->string('user_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cicsa_assignation_id')->constrained('cicsa_assignations')->onDelete('cascade');
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
