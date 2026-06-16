<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade');
            $table->foreignId('registrado_por')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['inicial', 'aumento', 'bonificacion', 'descuento', 'ajuste']);
            $table->decimal('monto', 10, 2);
            $table->decimal('salario_anterior', 10, 2)->nullable();
            $table->decimal('salario_nuevo', 10, 2);
            $table->date('fecha');
            $table->text('motivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salarios');
    }
};
