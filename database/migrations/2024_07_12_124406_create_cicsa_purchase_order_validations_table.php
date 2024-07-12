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
        Schema::create('cicsa_purchase_order_validations', function (Blueprint $table) {
            $table->id();
            $table->date('validation_date');
            $table->string('materials_control');
            $table->string('supervisor');
            $table->string('warehouse');
            $table->string('boss');
            $table->string('liquidator');
            $table->string('superintendent');
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
        Schema::dropIfExists('cicsa_purchase_order_validations');
    }
};
