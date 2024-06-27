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
        Schema::create('price_guide2', function (Blueprint $table) {
            $table->id();
            $table->string('bidding_area');
            $table->double('unit_price');
            $table->foreignId('ha2_id')->constrained('huawei_anexes2')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_guide2');
    }
};
