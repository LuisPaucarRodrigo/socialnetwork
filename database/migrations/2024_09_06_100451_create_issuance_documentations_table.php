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
        Schema::create('issuance_documentations', function (Blueprint $table) {
            $table->id();
            $table->date('sctr');
            $table->date('policy_date');
            $table->string('policy_beneficiary');
            $table->date('emo_anexo_16');
            $table->date('emo_anexo_16_a');
            $table->date('first_aid');
            $table->date('electrical_risk');
            $table->date('safety_committee');
            $table->date('height_work');
            $table->string('claro');
            $table->string('ccip');
            $table->date('vericom_annual');
            $table->date('vericom');
            $table->date('epps_delivery');
            $table->foreignId('fixed_documentation_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuance_documentations');
    }
};
