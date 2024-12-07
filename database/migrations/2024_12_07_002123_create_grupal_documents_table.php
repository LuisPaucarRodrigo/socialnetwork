<?php

use App\Constants\DocumentConstants;
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
        Schema::create('grupal_documents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', DocumentConstants::grupalDouments());
            $table->string('archive');
            $table->date('date');
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupal_documents');
    }
};
