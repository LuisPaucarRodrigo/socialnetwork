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
        Schema::create('payment_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('zone');
            $table->float('amount');
            $table->text('description');
            $table->string('account_number');
            $table->string('bank');
            $table->string('ruc');
            $table->string('beneficiary');
            $table->string('document')->nullable();
            $table->string('proof_payment')->nullable();
            $table->boolean('is_validated')->nullable();
            $table->string('reason_rejection')->nullable();
            $table->foreignId('cost_line_id')->constrained();
            $table->foreignId('provider_id')->constrained()->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_approvals');
    }
};
