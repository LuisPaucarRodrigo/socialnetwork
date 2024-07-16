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
        Schema::create('pre_report_huawei_generals', function (Blueprint $table) {
            $table->id();
            $table->string('site');
            $table->string('elaborated');
            $table->string('code');
            $table->string('name');
            $table->string('address');
            $table->string('reference');
            $table->string('access');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_report_huawei_generals');
    }
};
