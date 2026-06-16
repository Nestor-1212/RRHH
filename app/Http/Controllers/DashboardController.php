<?php

namespace App\Http\Controllers;

use App\Models\Amonestacion;
use App\Models\Asistencia;
use App\Models\Candidato;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Evaluacion;
use App\Models\Entrevista;
use App\Models\Salida;
use App\Models\Vacante;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();

        // KPIs — 5 min cache
        $kpis = Cache::remember('dashboard.kpis', 300, function () use ($now) {
            $totalActivos    = Empleado::where('estado', 'activo')->count();
            $totalRetirados  = Empleado::where('estado', 'retirado')->count();
            $nuevosIngresos  = Empleado::where('estado', 'activo')
                ->whereYear('fecha_ingreso', $now->year)
                ->whereMonth('fecha_ingreso', $now->month)
                ->count();
            $vacantesAbiertas    = Vacante::where('estado', 'disponible')->count();
            $candidatosEnProceso = Candidato::whereIn('estado', ['activo', 'en_proceso'])->count();
            $amonestacionesMes   = Amonestacion::whereYear('fecha', $now->year)
                ->whereMonth('fecha', $now->month)
                ->count();

            // Tasa de asistencia del mes actual
            $diasLaborados = Asistencia::whereYear('fecha', $now->year)
                ->whereMonth('fecha', $now->month)
                ->count();
            $presentes = Asistencia::whereYear('fecha', $now->year)
                ->whereMonth('fecha', $now->month)
                ->whereIn('tipo', ['normal', 'tardanza'])
                ->count();
            $tasaAsistencia = $diasLaborados > 0
                ? round(($presentes / $diasLaborados) * 100, 1)
                : 0;

            return compact(
                'totalActivos', 'totalRetirados', 'nuevosIngresos',
                'vacantesAbiertas', 'candidatosEnProceso', 'amonestacionesMes',
                'tasaAsistencia'
            );
        });

        // Headcount por departamento
        $headcount = Cache::remember('dashboard.headcount', 300, fn() =>
            Departamento::select('departamentos.nombre')
                ->selectRaw('COUNT(empleados.id) as total')
                ->leftJoin('empleados', fn($j) =>
                    $j->on('departamentos.id', '=', 'empleados.departamento_id')
                      ->where('empleados.estado', 'activo')
                )
                ->where('departamentos.activo', true)
                ->groupBy('departamentos.id', 'departamentos.nombre')
                ->having('total', '>', 0)
                ->orderByDesc('total')
                ->get()
        );

        // Gráfico rotación anual (ingresos vs salidas por mes)
        $rotacion = Cache::remember('dashboard.rotacion.' . $now->year, 300, function () use ($now) {
            $ingresosPorMes = Empleado::selectRaw('MONTH(fecha_ingreso) as mes, COUNT(*) as total')
                ->whereYear('fecha_ingreso', $now->year)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $salidasPorMes = Salida::selectRaw('MONTH(fecha_salida) as mes, COUNT(*) as total')
                ->whereYear('fecha_salida', $now->year)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $meses = $ingresos = $salidas = [];
            for ($i = 1; $i <= 12; $i++) {
                $meses[]   = Carbon::create(null, $i)->translatedFormat('M');
                $ingresos[] = $ingresosPorMes->get($i, 0);
                $salidas[]  = $salidasPorMes->get($i, 0);
            }
            return compact('meses', 'ingresos', 'salidas');
        });

        // Promedio evaluaciones por departamento
        $evalPorDepto = Cache::remember('dashboard.eval_depto', 300, fn() =>
            DB::table('evaluaciones')
                ->join('empleados', 'evaluaciones.empleado_id', '=', 'empleados.id')
                ->join('departamentos', 'empleados.departamento_id', '=', 'departamentos.id')
                ->select('departamentos.nombre')
                ->selectRaw('ROUND(AVG(evaluaciones.puntaje_total), 1) as promedio')
                ->selectRaw('COUNT(evaluaciones.id) as total_evals')
                ->groupBy('departamentos.id', 'departamentos.nombre')
                ->orderByDesc('promedio')
                ->get()
        );

        // Entrevistas recientes
        $entrevistasRecientes = Entrevista::with(['candidato', 'vacante'])
            ->latest()
            ->limit(5)
            ->get();

        // Top evaluaciones recientes
        $topEvaluaciones = Evaluacion::with('empleado.departamento')
            ->where('calificacion', 'excelente')
            ->latest()
            ->limit(4)
            ->get();

        return view('dashboard', array_merge($kpis, $rotacion, compact(
            'headcount', 'evalPorDepto', 'entrevistasRecientes', 'topEvaluaciones'
        )));
    }
}
