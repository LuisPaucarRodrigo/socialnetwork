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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->double('amount',10, 2);
            $table->text('description')->nullable();
            $table->boolean('state')->nullable()->default(false);
            $table->string('operation_number')->nullable();
            $table->date('register_date')->nullable();
            $table->date('date')->nullable();
            $table->string('payment_doc')->nullable();
            $table->foreignId('purchase_quote_id')->constrained('purchase_quotes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
