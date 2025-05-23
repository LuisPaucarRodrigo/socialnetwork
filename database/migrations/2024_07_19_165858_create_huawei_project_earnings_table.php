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
        Schema::create('huawei_project_earnings', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->string('unit');
            $table->double('unit_price');
            $table->foreignId('huawei_pa_id')->constrained('huawei_project_assignations')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('observation');
            $table->string('evidence');
            $table->string('goal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_project_earnings');
    }
};
