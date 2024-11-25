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
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->integer('credit_to')->nullable();
            $table->double('amount')->nullable();
            $table->boolean('state_detraction')->default(true);

            $table->date('deposit_date')->nullable();
            $table->string('transaction_number_current')->nullable();
            $table->float('checking_account_amount')->nullable();
            $table->date('deposit_date_bank')->nullable();
            $table->string('transaction_number_bank')->nullable();
            $table->float('amount_bank')->nullable();
            $table->string('document');
            
            $table->string('user_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cicsa_assignation_id')->nullable()->constrained('cicsa_assignations')->onDelete('cascade');
            $table->foreignId('cicsa_purchase_order_id')->onDelete('cascade');
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
