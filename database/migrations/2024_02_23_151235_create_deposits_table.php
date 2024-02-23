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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->date('operation_date');
            $table->string('operation_code')->unique();
            $table->string('operation_description');
            $table->text('denomination');
            $table->text('observation')->nullable()->default(null);
            $table->double('comission')->nullable()->default(null);
            $table->string('total_type');
            $table->double('total')->nullable();
            $table->foreignId('accounting_account_id')
                ->nullable()
                ->constrained('accounting_accounts')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
