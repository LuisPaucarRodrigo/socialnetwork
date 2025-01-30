<?php

use App\Constants\ProjectConstants;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('priority');
            $table->text('description');
            $table->string('status')->nullable();
            $table->integer('position')->nullable()->unsigned();
            $table->string('year')->nullable();
            $table->double('initial_budget', 8, 2)->default(0);
            $table->foreignId('preproject_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('cost_line_id')->nullable()->constrained('cost_lines')->nullOnDelete();
            $table->boolean('is_accepted')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
