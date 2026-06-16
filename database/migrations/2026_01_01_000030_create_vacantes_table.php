<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_puesto');
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->text('descripcion')->nullable();
            $table->text('requisitos')->nullable();
            $table->decimal('salario_ofrecido', 10, 2)->nullable();
            $table->enum('tipo_contrato', ['indefinido', 'definido', 'servicios', 'pasantia'])->default('indefinido');
            $table->date('fecha_apertura');
            $table->date('fecha_cierre')->nullable();
            $table->enum('estado', ['disponible', 'en_proceso', 'cerrada'])->default('disponible');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacantes');
    }
};
