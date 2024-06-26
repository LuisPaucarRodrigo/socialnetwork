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
        Schema::create('preproject_quote_services', function (Blueprint $table) {
            $table->id();
            $table->integer('days');
            $table->double('profit_margin');
            $table->double('rent_price');
            $table->foreignId('preproject_quote_id')->contrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->onDelete('set null');
            $table->foreignId('resource_entry_id')->nullable()->onDelete('set null');
            $table->boolean('state')->nullable();
            $table->double('final_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preproject_quote_services');
    }
};
