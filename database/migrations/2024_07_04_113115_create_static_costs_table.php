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
        Schema::create('static_costs', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
            $table->string('ruc');
            $table->string('zone');
            $table->string('type_doc');
            $table->string('operation_number')->nullable();
            $table->date('operation_date')->nullable();
            $table->string('doc_number')->nullable();
            $table->date('doc_date');
            $table->double('amount');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('provider_id')
                ->nullable()
                ->constrained('providers')
                ->onDelete('set null');
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->integer('igv')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_costs');
    }
};
