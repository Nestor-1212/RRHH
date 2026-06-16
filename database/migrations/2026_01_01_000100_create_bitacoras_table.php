<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('candidato_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->string('tipo');
            $table->text('descripcion');
            $table->nullableMorphs('referencia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
