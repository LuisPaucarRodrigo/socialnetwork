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
        Schema::create('sn_sot_control', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sot_id')->constrained('sn_sots')->onDelete('cascade');
            $table->string('p_bad_installation')->nullable();
            $table->string('p_no_rf')->nullable();
            $table->string('p_rejections')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_sot_control');
    }
};
