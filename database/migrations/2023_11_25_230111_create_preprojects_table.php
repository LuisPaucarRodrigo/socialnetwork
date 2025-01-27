<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\ProjectConstants;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preprojects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('subcustomer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('code');
            $table->string('cpe')->nullable();
            $table->text('description');
            $table->date('date');
            $table->text('observation')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('cost_line_id')->nullable()->constrained('cost_lines')->nullOnDelete();
            $table->foreignId('title_id')->nullable()->constrained('titles')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preprojects');
    }
};
