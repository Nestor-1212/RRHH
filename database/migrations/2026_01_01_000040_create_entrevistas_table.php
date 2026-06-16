<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrevistas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained()->onDelete('cascade');
            $table->foreignId('vacante_id')->constrained()->onDelete('cascade');
            $table->foreignId('entrevistador_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('fecha_entrevista');
            $table->enum('tipo', ['inicial', 'tecnica', 'final']);
            $table->unsignedTinyInteger('puntaje_experiencia')->default(0);
            $table->unsignedTinyInteger('puntaje_conocimiento')->default(0);
            $table->unsignedTinyInteger('puntaje_comunicacion')->default(0);
            $table->unsignedTinyInteger('puntaje_actitud')->default(0);
            $table->unsignedTinyInteger('puntaje_disponibilidad')->default(0);
            $table->unsignedTinyInteger('puntaje_total')->default(0);
            $table->text('comentarios')->nullable();
            $table->text('fortalezas')->nullable();
            $table->text('debilidades')->nullable();
            $table->enum('resultado', ['pendiente', 'seleccionado', 'no_seleccionado'])->default('pendiente');
            $table->enum('motivo_rechazo', [
                'falta_experiencia',
                'otro_candidato',
                'no_cumple_requisitos',
                'pretension_salarial',
                'no_aprobado',
                'otro'
            ])->nullable();
            $table->text('detalle_rechazo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrevistas');
    }
};
