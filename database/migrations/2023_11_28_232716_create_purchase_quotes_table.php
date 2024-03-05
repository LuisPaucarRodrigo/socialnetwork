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
        Schema::create('purchase_quotes', function (Blueprint $table) {
            $table->id();
            $table->date('quote_deadline');
            $table->string('purchase_doc');
            $table->boolean('state')->nullable()->default(null);
            $table->boolean('igv')->nullable()->default(false);
            $table->integer('deliverable_time');
            $table->text('payment_type');
            $table->string('account_number'); 
            $table->string('currency');
            $table->double('change_value', 10,3)->nullable()->default(null);
            $table->foreignId('purchasing_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_quotes');
    }
};
