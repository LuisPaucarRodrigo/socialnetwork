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
        Schema::create('huawei_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_entry_detail_id')->constrained('huawei_entry_details')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_refunds');
    }
};
