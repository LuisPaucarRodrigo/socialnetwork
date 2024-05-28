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
        Schema::create('sn_sot_operation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sot_id')->constrained('sn_sots')->onDelete('cascade');
            $table->string('i_state')->nullable();
            $table->text('additionals')->nullable();
            $table->string('photo_report')->nullable();
            $table->date('ic_date')->nullable();
            $table->double('bill_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_sot_operation');
    }
};
