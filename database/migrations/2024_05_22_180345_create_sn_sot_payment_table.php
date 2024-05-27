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
        Schema::create('sn_sot_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('s_n_sot_id')->constrained('sn_sots')->onDelete('cascade');
            $table->string('sot_bill')->nullable();
            $table->string('sot_bill_date')->nullable();
            $table->string('bill')->nullable();
            $table->string('bill_date')->nullable();
            $table->string('charge')->nullable();
            $table->string('charge_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_sot_payment');
    }
};
