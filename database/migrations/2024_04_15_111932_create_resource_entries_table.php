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
        Schema::create('resource_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->string('serial_number')->nullable();
            $table->string('referral_guide');
            $table->decimal('entry_price');
            $table->boolean('state')->nullable();
            $table->string('condition')->nullable();
            $table->foreignId('purchase_product_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_entries');
    }
};
