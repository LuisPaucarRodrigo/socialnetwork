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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('resource_description_id')->nullable()->constrained('resource_descriptions')->onDelete('set null');
            $table->foreignId('resource_category_id')->nullable()->constrained('resource_category')->onDelete('set null');
            $table->string('serial_number');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->integer('depreciation')->nullable();
            $table->boolean('conditional_rent');
            $table->decimal('unit_price_depreciation',10,2)->nullable();
            $table->text('observations')->nullable();
            $table->date('adquisition_date');
            $table->string('current_location');
            $table->string('unique_identification')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
