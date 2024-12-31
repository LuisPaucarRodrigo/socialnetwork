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
        Schema::create('project_quote_valuations', function (Blueprint $table) {
            $table->id();
            $table->integer('days');
            $table->string('unit');
            $table->integer('metrado');
            $table->float('unit_value');
            $table->text('description');
            $table->foreignId('project_quote_id')->constrained('project_quotes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_quote_valuations');
    }
};
