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
        Schema::create('special_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('cpe');
            $table->string('referral_guide');
            $table->date('entry_date');
            $table->string('sub_warehouse');
            $table->integer('quantity');
            $table->string('product_serial_number');
            $table->text('entry_observations');
            $table->foreignId('purchase_product_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_inventories');
    }
};
