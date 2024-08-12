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
        Schema::create('checklist_toolkits', function (Blueprint $table) {
            $table->id();
            $table->text('reason');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('personal_2');
            $table->string('zone');
            //KIT TOOLS
            $table->string('mosqueton');
            $table->string('pelacable');
            $table->string('crimpeadora');
            $table->string('crimpeadorater');
            $table->string('limas');
            $table->string('allen');
            $table->string('readline');
            $table->string('impacto');
            $table->string('dielectricos');
            $table->string('corte');
            $table->string('fuerza');
            $table->string('recto');
            $table->string('francesas');
            $table->string('sierra');
            $table->string('silicona');
            $table->string('polea');
            $table->string('wincha');
            $table->string('eslinga');
            $table->string('botiquin');
            $table->string('brocas');
            $table->string('sacabocado');
            $table->string('extractor');
            $table->string('maletagrande');
            $table->string('maletamediana');
            //KIT CONTROL
            $table->string('juego_llaves');
            $table->string('juego_bravos');
            $table->string('cuter');
            $table->string('arnes');
            //EQUIPMENT CONTROL
            $table->string('hidrolavadora');
            $table->string('sopladora');
            $table->string('megometro');
            $table->string('telurometro');
            $table->string('aperimetrica');
            $table->string('manometro');
            $table->string('pirometro');
            $table->string('laptop');
            $table->string('taladro');
            $table->string('brujula');
            $table->string('inclinometro');
            $table->string('linterna');
            $table->string('powermeter');
            $table->string('pistola');
            $table->string('pertiga');
            $table->string('soga75');
            $table->string('escalera');
            $table->string('extension');
            $table->string('pistolaestano');
            $table->string('escaleratijera');
            $table->string('pulverizadora');
            $table->string('testeadorrj45');
            $table->string('cableconsolared');
            $table->string('adaptadorred');

            $table->text('observations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_toolkits');
    }
};
