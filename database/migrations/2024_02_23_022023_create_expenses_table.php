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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('type_cdp');
            $table->string('gang');
            $table->string('person');
            $table->date('date');
            $table->string('number')->nullable();
            $table->string('series');            
            $table->string('ruc')->nullable();
            $table->decimal('price',8,2);
            $table->string('description_expenses');
            $table->string('type_expenses');
            $table->string('percentaje')->nullable();
            $table->timestamps();

            $table->unique(['number', 'series']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
