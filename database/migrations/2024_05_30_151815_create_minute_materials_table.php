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
        Schema::create('minute_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snsotop_id')
                  ->constrained('sn_sot_operation')
                  ->onDelete('cascade');
            $table->string('material');
            $table->string('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minute_materials');
    }
};
