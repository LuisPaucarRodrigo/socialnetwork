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
        Schema::create('huawei_balance_earnings', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->double('amount');
            $table->date('invoice_date');
            $table->date('deposit_date')->nullable();
            $table->double('main_amount')->nullable();
            $table->string('main_op_number')->nullable();
            $table->double('detraction_amount')->nullable();
            $table->string('detraction_op_number')->nullable();
            $table->string('detraction_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_balance_earnings');
    }
};
