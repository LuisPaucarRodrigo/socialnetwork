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
        Schema::create('preprojects', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->string('code');
            $table->string('phone',9);
            $table->string('description');
            $table->string('address');
            $table->date('date');
            $table->string('observation');
            $table->string('facade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preprojects');
    }
};
