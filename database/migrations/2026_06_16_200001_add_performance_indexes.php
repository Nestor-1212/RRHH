<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // empleados — columnas de búsqueda y filtro frecuente
        Schema::table('empleados', function (Blueprint $table) {
            $table->index('estado');
            $table->index('departamento_id');
            $table->index('fecha_ingreso');
            $table->index(['nombre', 'apellido']);
        });

        // candidatos
        Schema::table('candidatos', function (Blueprint $table) {
            $table->index('estado');
        });

        // vacantes
        Schema::table('vacantes', function (Blueprint $table) {
            $table->index('estado');
            $table->index('departamento_id');
        });

        // asistencias — filtros por empleado y fecha son los más comunes
        Schema::table('asistencias', function (Blueprint $table) {
            $table->index(['empleado_id', 'fecha']);
            $table->index('tipo');
        });

        // amonestaciones
        Schema::table('amonestaciones', function (Blueprint $table) {
            $table->index(['empleado_id', 'fecha']);
        });

        // evaluaciones
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->index('empleado_id');
            $table->index('calificacion');
        });

        // salarios
        Schema::table('salarios', function (Blueprint $table) {
            $table->index(['empleado_id', 'fecha']);
        });

        // bitacoras
        Schema::table('bitacoras', function (Blueprint $table) {
            $table->index(['empleado_id', 'fecha']);
            $table->index('tipo');
        });

        // entrevistas
        Schema::table('entrevistas', function (Blueprint $table) {
            $table->index(['candidato_id', 'vacante_id']);
            $table->index('resultado');
        });
    }

    public function down(): void
    {
        Schema::table('empleados',    fn($t) => $t->dropIndex(['estado', 'departamento_id', 'fecha_ingreso']));
        Schema::table('candidatos',   fn($t) => $t->dropIndex(['estado']));
        Schema::table('vacantes',     fn($t) => $t->dropIndex(['estado', 'departamento_id']));
        Schema::table('asistencias',  fn($t) => $t->dropIndex(['tipo']));
        Schema::table('evaluaciones', fn($t) => $t->dropIndex(['calificacion']));
        Schema::table('entrevistas',  fn($t) => $t->dropIndex(['resultado']));
    }
};
