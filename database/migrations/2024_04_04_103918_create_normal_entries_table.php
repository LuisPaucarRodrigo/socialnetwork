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
        Schema::create('normal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained();
            $table->foreignId('purchase_product_id')->constrained();
            $table->string('referral_guide');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normal_entries');
    }
};
