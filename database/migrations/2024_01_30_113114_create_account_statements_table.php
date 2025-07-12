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
        Schema::create('account_statements', function (Blueprint $table) {
            $table->id();
            $table->date('operation_date');
            $table->string('operation_number')->nullable();
            $table->string('description');
            $table->double('charge')->nullable();
            $table->double('payment')->nullable();
            $table->timestamps();
            $table->unique(['operation_date', 'operation_number']);

            $table->index('operation_number');
            $table->index('operation_date');
            $table->index(['operation_number', 'operation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_statements');
    }
};
