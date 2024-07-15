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
        Schema::create('cicsa_charge_areas', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('payment_date');
            $table->date('deposit_date')->nullable();
            $table->double('amount');
            $table->string('user_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cicsa_assignation_id')->constrained('cicsa_assignations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_charge_areas');
    }
};
