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
        Schema::create('cicsa_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->date('oc_date')->nullable();
            $table->string('oc_number')->nullable();
            $table->string('master_format');
            $table->string('item3456');
            $table->string('budget');
            $table->string('document');
            $table->string('observation')->nullable();
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
        Schema::dropIfExists('cicsa_purchase_orders');
    }
};
