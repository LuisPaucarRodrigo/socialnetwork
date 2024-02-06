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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("lastname");
            $table->string("cropped_image")->nullable();
            $table->string("gender");
            $table->string("state_civil");
            $table->date("birthdate");
            $table->string("dni",8)->unique();
            $table->string("email")->unique();
            $table->string("email_company")->unique()->nullable();
            $table->string("phone1",9)->unique();
            $table->string("phone2",9)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
