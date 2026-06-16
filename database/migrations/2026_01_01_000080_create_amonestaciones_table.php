<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amonestaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade');
            $table->foreignId('registrado_por')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->enum('tipo', ['llamado_atencion', 'verbal', 'escrita', 'suspension']);
            $table->text('motivo');
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('dias_suspension')->default(0);
            $table->timestamps();
        });

        Schema::create('amonestacion_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amonestacion_id')->constrained('amonestaciones')->onDelete('cascade');
            $table->enum('tipo', ['documento', 'evidencia', 'fotografia', 'otro']);
            $table->string('nombre');
            $table->string('archivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amonestacion_archivos');
        Schema::dropIfExists('amonestaciones');
    }
};
