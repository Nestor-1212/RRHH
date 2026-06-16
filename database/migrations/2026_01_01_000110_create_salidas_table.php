<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade');
            $table->foreignId('registrado_por')->constrained('users')->onDelete('cascade');
            $table->date('fecha_salida');
            $table->enum('tipo', ['renuncia', 'despido', 'finalizacion_contrato', 'jubilacion', 'otro']);
            $table->string('ultimo_cargo');
            $table->decimal('ultimo_salario', 10, 2);
            $table->text('motivo')->nullable();
            $table->timestamps();
        });

        Schema::create('salida_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['carta_renuncia', 'carta_despido', 'acta_entrega', 'liquidacion', 'otro']);
            $table->string('nombre');
            $table->string('archivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salida_documentos');
        Schema::dropIfExists('salidas');
    }
};
