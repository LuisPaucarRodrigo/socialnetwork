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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('path');
            $table->string('comment');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->double('version');
            $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade');
            $table->boolean('approve_status')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE archives ADD CONSTRAINT chk_version_non_negative CHECK (version >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
