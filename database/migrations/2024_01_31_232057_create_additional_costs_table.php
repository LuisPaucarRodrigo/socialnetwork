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
        Schema::create('additional_costs', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
            $table->string('ruc');
            $table->string('type_doc');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->double('amount');
            $table->string('description')->nullable();
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_costs');
    }
};
