<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_resource_liquidation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_resource_id')
                ->nullable()
                ->constrained('project_resource')
                ->onDelete('cascade');
            $table->string('liquidated_quantity');
            $table->string('refund_quantity');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_resource_liquidation');
    }
};
