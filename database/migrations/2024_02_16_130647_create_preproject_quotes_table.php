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
        Schema::create('preproject_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('date');
            $table->string('supervisor');
            $table->string('boss');
            $table->string('rev');
            $table->integer('deliverable_time');
            $table->integer('validity_time');
            $table->string('deliverable_place');
            $table->string('payment_type');
            $table->text('observations')->nullable();
            $table->boolean('state')->default(false);
            $table->foreignId('preproject_id')->constrained('preprojects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preproject_quotes');
    }
};
