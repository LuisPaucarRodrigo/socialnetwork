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
        Schema::create('backlog_sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_id');
            $table->text('site_name');
            $table->string('site_region');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('site_type_label');
            $table->string('site_priority');
            $table->string('access_type');
            $table->string('contratist');
            $table->string('energy_type');
            $table->string('room_type');
            $table->string('district');
            $table->string('address');
            $table->string('department');
            $table->string('province');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlog_sites');
    }
};
