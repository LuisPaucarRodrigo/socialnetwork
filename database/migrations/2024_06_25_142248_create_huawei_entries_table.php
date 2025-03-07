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
            //buena paucar

        Schema::create('huawei_entries', function (Blueprint $table) {
            $table->id();
            $table->string('guide_number')->nullable();
            $table->date('entry_date')->nullable();
            $table->text('observation')->nullable();
            $table->string('archive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huawei_entries');
    }
};
