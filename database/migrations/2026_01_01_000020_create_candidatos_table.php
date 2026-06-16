<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('cedula')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->string('hoja_vida')->nullable();
            $table->decimal('aspiracion_salarial', 10, 2)->nullable();
            $table->enum('estado', ['activo', 'en_proceso', 'contratado', 'descartado', 'archivado'])->default('activo');
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('candidato_estudios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained()->onDelete('cascade');
            $table->enum('nivel', ['primaria', 'secundaria', 'bachillerato', 'tecnico', 'universitario', 'postgrado', 'maestria', 'doctorado']);
            $table->string('institucion');
            $table->string('carrera')->nullable();
            $table->year('anio_inicio')->nullable();
            $table->year('anio_fin')->nullable();
            $table->boolean('graduado')->default(false);
            $table->timestamps();
        });

        Schema::create('candidato_experiencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained()->onDelete('cascade');
            $table->string('empresa');
            $table->string('cargo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('actualmente')->default(false);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('candidato_referencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('relacion');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('candidato_idiomas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained()->onDelete('cascade');
            $table->string('idioma');
            $table->enum('nivel', ['basico', 'intermedio', 'avanzado', 'nativo']);
            $table->timestamps();
        });

        Schema::create('candidato_habilidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->constrained()->onDelete('cascade');
            $table->string('habilidad');
            $table->string('nivel')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidato_habilidades');
        Schema::dropIfExists('candidato_idiomas');
        Schema::dropIfExists('candidato_referencias');
        Schema::dropIfExists('candidato_experiencias');
        Schema::dropIfExists('candidato_estudios');
        Schema::dropIfExists('candidatos');
    }
};
