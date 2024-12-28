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
        Schema::create('cicsa_installations', function (Blueprint $table) {
            $table->id();
            $table->date('pext_date')->nullable();
            $table->date('pint_date')->nullable();
            $table->float('pint_amount')->nullable();
            $table->float('pext_amount')->nullable();
            $table->double('projected_amount')->nullable();
            $table->string('conformity');
            $table->string('report');
            $table->date('shipping_report_date')->nullable();
            $table->string('coordinator');
            $table->text('observation')->nullable();
            $table->string('user_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cicsa_assignation_id')->constrained('cicsa_assignations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicsa_installations');
    }
};
