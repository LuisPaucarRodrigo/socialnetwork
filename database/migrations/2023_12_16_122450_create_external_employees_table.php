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
        Schema::create('external_employees', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("lastname");
            $table->string("expense_line");
            $table->string("dni", 8)->unique();
            $table->date("birthdate");
            $table->string("cropped_image")->nullable();
            $table->string("gender");
            $table->string("address");
            $table->string("phone1", 9)->unique();
            $table->string("email")->unique();
            $table->string("email_company")->unique()->nullable();
            $table->string("salary");
            $table->boolean("sctr");
            $table->string('curriculum_vitae')->nullable();

            $table->boolean('l_policy')->nullable();
            $table->date('sctr_exp_date')->nullable();
            $table->date('policy_exp_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_employees');
    }
};
