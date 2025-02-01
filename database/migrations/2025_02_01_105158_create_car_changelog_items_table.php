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
        Schema::create('car_changelog_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('observation');
            $table->foreignId('car_changelog_id')->constrained('car_changelogs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_changelog_items');
    }
};
