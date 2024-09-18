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
            $table->date('validation_date')->nullable();
            $table->string('materials_control')->default('Pendiente');
            $table->string('file_validation')->default('Pendiente');
            $table->string('supervisor')->default('Pendiente');
            $table->string('warehouse')->default('Pendiente');
            $table->string('boss')->default('Pendiente');
            $table->string('liquidator')->default('Pendiente');
            $table->string('superintendent')->default('Pendiente');
            $table->string('observations')->nullable();
            $table->string('user_name')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cicsa_assignation_id')->nullable()->constrained('cicsa_assignations')->onDelete('cascade');
            $table->foreignId('cicsa_purchase_order_id')->onDelete('cascade');
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
