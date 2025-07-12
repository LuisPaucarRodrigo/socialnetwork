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
        Schema::create('room_documents', function (Blueprint $table) {
            $table->id();
            $table->string('ownership_card')->nullable();
            $table->string('technical_review')->nullable();
            $table->date('technical_review_date')->nullable();
            $table->string('soat')->nullable();
            $table->date('soat_date')->nullable();
            $table->string('insurance')->nullable();
            $table->date('insurance_date')->nullable();
            $table->string('rental_contract')->nullable();
            $table->date('rental_contract_date')->nullable();
            $table->string('address_web')->nullable();
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_documents');
    }
};
