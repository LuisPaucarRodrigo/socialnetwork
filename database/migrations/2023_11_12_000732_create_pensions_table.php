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
        Schema::create('pensions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            //Se cambiaron a nullable para no borrarlo porque talves mas adelante se use.
            $table->decimal('values', 10, 4)->nullable();
            $table->decimal('values_seg', 10, 4)->nullable();
            $table->foreignId('payroll_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensions');
    }
};
