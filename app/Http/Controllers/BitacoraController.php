<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Empleado;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function show(Empleado $empleado)
    {
        $bitacoras = $empleado->bitacoras()->orderByDesc('fecha')->paginate(30);
        return view('bitacoras.show', compact('empleado', 'bitacoras'));
    }
}
