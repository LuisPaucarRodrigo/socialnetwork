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
        Schema::create('sn_sot_liquidation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sot_id')->constrained('sn_sots')->onDelete('cascade');
            $table->string('up_minutes')->nullable();
            $table->string('liquidation')->nullable();
            $table->string('down_warehouse')->nullable();
            $table->string('liquidation_date')->nullable();
            $table->string('sot_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_sot_liquidation');
    }
};
