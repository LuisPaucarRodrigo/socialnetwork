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
        Schema::create('preprojects_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preproject_id')->constrained('preprojects')->onDelete('cascade');
            $table->foreignId('customer_contact_id')->nullable()->constrained('customers_contacts')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preprojects_contacts');
    }
};
