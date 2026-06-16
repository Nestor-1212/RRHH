<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\VacanteController;
use App\Http\Controllers\EntrevistaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\SalarioController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AmonestacionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Departamentos
    Route::resource('departamentos', DepartamentoController::class)->except(['show']);

    // Candidatos
    Route::resource('candidatos', CandidatoController::class);
    Route::post('candidatos/{candidato}/estudios', [CandidatoController::class, 'storeEstudio'])->name('candidatos.estudios.store');
    Route::delete('candidatos/estudios/{estudio}', [CandidatoController::class, 'destroyEstudio'])->name('candidatos.estudios.destroy');
    Route::post('candidatos/{candidato}/experiencias', [CandidatoController::class, 'storeExperiencia'])->name('candidatos.experiencias.store');
    Route::delete('candidatos/experiencias/{experiencia}', [CandidatoController::class, 'destroyExperiencia'])->name('candidatos.experiencias.destroy');
    Route::post('candidatos/{candidato}/referencias', [CandidatoController::class, 'storeReferencia'])->name('candidatos.referencias.store');
    Route::delete('candidatos/referencias/{referencia}', [CandidatoController::class, 'destroyReferencia'])->name('candidatos.referencias.destroy');
    Route::post('candidatos/{candidato}/idiomas', [CandidatoController::class, 'storeIdioma'])->name('candidatos.idiomas.store');
    Route::delete('candidatos/idiomas/{idioma}', [CandidatoController::class, 'destroyIdioma'])->name('candidatos.idiomas.destroy');
    Route::post('candidatos/{candidato}/habilidades', [CandidatoController::class, 'storeHabilidad'])->name('candidatos.habilidades.store');
    Route::delete('candidatos/habilidades/{habilidad}', [CandidatoController::class, 'destroyHabilidad'])->name('candidatos.habilidades.destroy');

    // Vacantes
    Route::resource('vacantes', VacanteController::class);

    // Entrevistas
    Route::resource('entrevistas', EntrevistaController::class);

    // Empleados
    Route::resource('empleados', EmpleadoController::class);
    Route::post('empleados/{empleado}/documentos', [EmpleadoController::class, 'storeDocumento'])->name('empleados.documentos.store');
    Route::delete('empleados/documentos/{documento}', [EmpleadoController::class, 'destroyDocumento'])->name('empleados.documentos.destroy');
    Route::get('empleados/{empleado}/bitacora', [BitacoraController::class, 'show'])->name('empleados.bitacora');

    // Salarios
    Route::get('empleados/{empleado}/salarios', [SalarioController::class, 'index'])->name('salarios.index');
    Route::get('empleados/{empleado}/salarios/nuevo', [SalarioController::class, 'create'])->name('salarios.create');
    Route::post('empleados/{empleado}/salarios', [SalarioController::class, 'store'])->name('salarios.store');

    // Asistencias
    Route::resource('asistencias', AsistenciaController::class)->except(['show']);

    // Amonestaciones
    Route::resource('amonestaciones', AmonestacionController::class);
    Route::delete('amonestaciones/archivos/{archivo}', [AmonestacionController::class, 'destroyArchivo'])->name('amonestaciones.archivos.destroy');

    // Evaluaciones
    Route::resource('evaluaciones', EvaluacionController::class);

    // Salidas
    Route::resource('salidas', SalidaController::class)->only(['index', 'create', 'store', 'show']);
    Route::delete('salidas/documentos/{documento}', [SalidaController::class, 'destroyDocumento'])->name('salidas.documentos.destroy');

    // Usuarios
    Route::resource('users', UserController::class)->except(['show']);

});

require __DIR__.'/auth.php';
