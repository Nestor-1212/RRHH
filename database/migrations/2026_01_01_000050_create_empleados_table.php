<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidato_id')->nullable()->constrained()->onDelete('set null');
            $table->string('codigo_empleado')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('cedula')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('foto')->nullable();
            $table->text('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('contacto_emergencia')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->date('fecha_ingreso');
            $table->string('cargo');
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('jefe_id')->nullable()->constrained('empleados')->onDelete('set null');
            $table->enum('tipo_contrato', ['indefinido', 'definido', 'servicios', 'pasantia'])->default('indefinido');
            $table->decimal('salario', 10, 2);
            $table->string('horario')->nullable();
            $table->enum('jornada', ['completa', 'parcial', 'mixta', 'nocturna'])->default('completa');
            $table->enum('estado', ['activo', 'inactivo', 'retirado'])->default('activo');
            $table->timestamps();
        });

        Schema::create('empleado_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['contrato', 'cedula', 'seguro_social', 'certificado', 'titulo', 'otro']);
            $table->string('nombre');
            $table->string('archivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_documentos');
        Schema::dropIfExists('empleados');
    }
};
