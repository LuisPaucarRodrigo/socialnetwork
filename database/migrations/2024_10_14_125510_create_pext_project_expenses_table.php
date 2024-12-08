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
        Schema::create('pext_project_expenses', function (Blueprint $table) {
            $table->id();
            $table->boolean('fixedOrAdditional');
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
            $table->boolean('is_accepted')->nullable();
            $table->boolean('state')->default(true);
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('cicsa_assignation_id')->nullable()->constrained();
            $table->foreignId('provider_id')
                ->nullable()
                ->constrained('providers')
                ->onDelete('set null');
            $table->foreignId('pext_project_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->integer('igv')->default('0');
            $table->foreignId('account_statement_id')
                ->nullable()
                ->constrained('account_statements')
                ->onDelete('set null');
            $table->timestamps();
            $table->unique(['ruc', 'doc_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pext_project_expenses');
    }
};
