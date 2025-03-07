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
        Schema::create('huawei_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('huawei_entry_id')->nullable()->constrained('huawei_entries')->onDelete('cascade');
            $table->foreignId('huawei_order_id')->nullable()->constrained('huawei_pending_orders')->onDelete('cascade');
            $table->date('order_date');
            $table->foreignId('huawei_material_id')->nullable()->constrained('huawei_materials')->onDelete('cascade');
            $table->foreignId('huawei_equipment_serie_id')->nullable()->constrained('huawei_equipment_series')->onDelete('cascade');
            $table->double('quantity');
            $table->double('unit_price')->nullable();
            $table->string('assigned_diu')->nullable();
            $table->text('observation')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('new_site')->nullable();
            $table->string('order_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_entry_details');
    }
};
