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
        Schema::create('huawei_price_guides', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->double('b1');
            $table->double('b2');
            $table->double('b3');
            $table->double('b4');
            $table->timestamps();
        });
            //buena paucar

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_price_guides');
    }
};
