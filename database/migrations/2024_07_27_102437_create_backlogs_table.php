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
        Schema::create('backlogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlog_site_id')->nullable()->constrained('backlog_sites')->onDelete('set null');
            $table->string('activity_type')->nullable();
            $table->string('task_id')->nullable();
            $table->string('system')->nullable();
            $table->string('subsystem')->nullable();
            $table->string('element')->nullable();
            $table->integer('quantity')->nullable();
            $table->date('event_date')->nullable();
            $table->date('report_date')->nullable();
            $table->text('event_element_description')->nullable();
            $table->string('state')->nullable();
            $table->text('commitment')->nullable();
            $table->date('c_start_date')->nullable();
            $table->date('c_end_date')->nullable();
            $table->string('responsible')->nullable();
            $table->string('criticity')->nullable();
            $table->string('conproco_area')->nullable();
            $table->string('origin_event')->nullable();
            $table->string('report_by')->nullable();
            $table->string('report')->nullable();
            $table->string('email_send')->nullable();
            $table->float('budget')->nullable();
            $table->string('budget_required')->nullable();
            $table->float('budget_2')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlogs');
    }
};
