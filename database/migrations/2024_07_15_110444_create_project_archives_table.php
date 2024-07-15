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
        Schema::create('project_archives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('path');
            $table->boolean('state')->default(true);
            $table->foreignId('upper_folder_id')->nullable()->constrained('folders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_archives');
    }
};
