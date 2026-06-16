<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Empleado;
use App\Models\Vacante;
use App\Models\Amonestacion;
use App\Models\Salida;
use App\Models\Entrevista;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmpleados     = Empleado::where('estado', 'activo')->count();
        $totalRetirados     = Empleado::where('estado', 'retirado')->count();
        $nuevosIngresos     = Empleado::where('estado', 'activo')
            ->whereMonth('fecha_ingreso', Carbon::now()->month)
            ->whereYear('fecha_ingreso', Carbon::now()->year)
            ->count();
        $vacantesAbiertas   = Vacante::where('estado', 'disponible')->count();
        $candidatosEnProceso = Candidato::whereIn('estado', ['activo', 'en_proceso'])->count();
        $amonestacionesMes  = Amonestacion::whereMonth('fecha', Carbon::now()->month)
            ->whereYear('fecha', Carbon::now()->year)
            ->count();
        $entrevistasRecientes = Entrevista::with(['candidato', 'vacante'])
            ->latest()
            ->take(5)
            ->get();
        $ingresosPorMes = Empleado::selectRaw('MONTH(fecha_ingreso) as mes, COUNT(*) as total')
            ->whereYear('fecha_ingreso', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        $salidasPorMes = Salida::selectRaw('MONTH(fecha_salida) as mes, COUNT(*) as total')
            ->whereYear('fecha_salida', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        $meses = [];
        $ingresos = [];
        $salidas = [];
        for ($i = 1; $i <= 12; $i++) {
            $meses[]   = Carbon::create(null, $i, 1)->translatedFormat('M');
            $ingresos[] = $ingresosPorMes->get($i)?->total ?? 0;
            $salidas[]  = $salidasPorMes->get($i)?->total ?? 0;
        }

        return view('dashboard', compact(
            'totalEmpleados', 'totalRetirados', 'nuevosIngresos',
            'vacantesAbiertas', 'candidatosEnProceso', 'amonestacionesMes',
            'entrevistasRecientes', 'meses', 'ingresos', 'salidas'
        ));
    }
}
