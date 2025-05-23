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
        Schema::create('cicsa_installation_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit');
            $table->integer('quantity');
            $table->string('type');
            $table->integer('total_quantity')->nullable();
            $table->integer('used_quantity');
            $table->foreignId('cicsa_installation_id')->constrained('cicsa_installations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_installation_materials');
    }
};
