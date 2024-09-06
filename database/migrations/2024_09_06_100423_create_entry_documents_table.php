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
        Schema::create('entry_documents', function (Blueprint $table) {
            $table->id();
            $table->string('registration_form');
            $table->date('contract');
            $table->string('certificate_discharge');
            $table->string('reading_sanctions');
            $table->string('reading_procedures');
            $table->string('annex_4_induction');
            $table->foreignId('fixed_documentation_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_documents');
    }
};
