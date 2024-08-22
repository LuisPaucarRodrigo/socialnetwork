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
            $table->string('zone');
            $table->string('type_doc');
            $table->string('doc_number')->nullable();
            $table->date('doc_date');
            $table->double('amount');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('provider_id')
                ->nullable()
                ->constrained('providers')
                ->onDelete('set null');
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['ruc', 'doc_number']);
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
