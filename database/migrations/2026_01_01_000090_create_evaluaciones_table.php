<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluador_id')->constrained('users')->onDelete('cascade');
            $table->string('periodo');
            $table->unsignedTinyInteger('puntaje_productividad')->default(0);
            $table->unsignedTinyInteger('puntaje_responsabilidad')->default(0);
            $table->unsignedTinyInteger('puntaje_trabajo_equipo')->default(0);
            $table->unsignedTinyInteger('puntaje_calidad')->default(0);
            $table->unsignedTinyInteger('puntaje_cumplimiento')->default(0);
            $table->unsignedTinyInteger('puntaje_total')->default(0);
            $table->enum('calificacion', ['excelente', 'bueno', 'regular', 'deficiente'])->nullable();
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
