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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('address');
            $table->string('phone1')->unique();
            $table->string('phone2')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('category');
            $table->string('zone');
            $table->string('ruc');
            $table->string('segment');
            $table->timestamps();

            $table->unique(['ruc', 'email', 'phone1']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
