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
            $table->string('operation_number')->nullable()->unique();
            $table->string('description');
            $table->double('charge')->nullable();
            $table->double('payment')->nullable();
            $table->timestamps();
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
