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
        Schema::create('sn_sots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_assignee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->text('description');
            $table->date('assigned_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sn_sots');
    }
};
