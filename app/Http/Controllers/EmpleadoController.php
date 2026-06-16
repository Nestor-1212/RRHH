<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\EmpleadoDocumento;
use App\Models\Candidato;
use App\Models\Departamento;
use App\Models\Salario;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Empleado::with('departamento');
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function($q) use ($b) {
                $q->where('nombre', 'like', "%$b%")
                  ->orWhere('apellido', 'like', "%$b%")
                  ->orWhere('cedula', 'like', "%$b%")
                  ->orWhere('codigo_empleado', 'like', "%$b%");
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('departamento_id')) {
            $query->where('departamento_id', $request->departamento_id);
        }
        $empleados     = $query->latest()->paginate(15)->withQueryString();
        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        return view('empleados.index', compact('empleados', 'departamentos'));
    }

    public function create(Request $request)
    {
        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        $jefes         = Empleado::where('estado', 'activo')->orderBy('nombre')->get();
        $candidato     = $request->filled('candidato_id')
            ? Candidato::find($request->candidato_id)
            : null;
        return view('empleados.create', compact('departamentos', 'jefes', 'candidato'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:100',
            'apellido'        => 'required|string|max:100',
            'cedula'          => 'required|string|max:30|unique:empleados,cedula',
            'fecha_ingreso'   => 'required|date',
            'cargo'           => 'required|string|max:150',
            'departamento_id' => 'required|exists:departamentos,id',
            'tipo_contrato'   => 'required',
            'salario'         => 'required|numeric|min:0',
            'jornada'         => 'required',
            'foto'            => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['foto', '_token']);
        $data['codigo_empleado'] = $this->generarCodigo();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('empleados/fotos', 'public');
        }

        $empleado = Empleado::create($data);

        Salario::create([
            'empleado_id'     => $empleado->id,
            'registrado_por'  => auth()->id(),
            'tipo'            => 'inicial',
            'monto'           => $data['salario'],
            'salario_anterior' => 0,
            'salario_nuevo'   => $data['salario'],
            'fecha'           => $data['fecha_ingreso'],
            'motivo'          => 'Salario inicial al ingreso.',
        ]);

        Bitacora::create([
            'empleado_id'    => $empleado->id,
            'candidato_id'   => $empleado->candidato_id,
            'fecha'          => $data['fecha_ingreso'],
            'tipo'           => 'ingreso',
            'descripcion'    => "Empleado ingresó a la empresa. Cargo: {$empleado->cargo}. Departamento: {$empleado->departamento->nombre}.",
            'referencia_type' => Empleado::class,
            'referencia_id'  => $empleado->id,
        ]);

        if ($empleado->candidato_id) {
            Candidato::where('id', $empleado->candidato_id)->update(['estado' => 'contratado']);
        }

        return redirect()->route('empleados.show', $empleado)->with('success', 'Empleado registrado correctamente.');
    }

    public function show(Empleado $empleado)
    {
        $empleado->load([
            'departamento', 'jefe', 'documentos', 'salarios.registrador',
            'amonestaciones', 'evaluaciones', 'bitacoras', 'salida',
        ]);
        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado)
    {
        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        $jefes         = Empleado::where('estado', 'activo')->where('id', '!=', $empleado->id)->orderBy('nombre')->get();
        return view('empleados.edit', compact('empleado', 'departamentos', 'jefes'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'nombre'          => 'required|string|max:100',
            'apellido'        => 'required|string|max:100',
            'cedula'          => 'required|string|max:30|unique:empleados,cedula,' . $empleado->id,
            'fecha_ingreso'   => 'required|date',
            'cargo'           => 'required|string|max:150',
            'departamento_id' => 'required|exists:departamentos,id',
            'tipo_contrato'   => 'required',
            'salario'         => 'required|numeric|min:0',
            'foto'            => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['foto', '_token', '_method']);

        if ($request->hasFile('foto')) {
            if ($empleado->foto) Storage::disk('public')->delete($empleado->foto);
            $data['foto'] = $request->file('foto')->store('empleados/fotos', 'public');
        }

        $empleado->update($data);
        return redirect()->route('empleados.show', $empleado)->with('success', 'Empleado actualizado.');
    }

    public function storeDocumento(Request $request, Empleado $empleado)
    {
        $request->validate([
            'tipo'    => 'required',
            'nombre'  => 'required|string|max:200',
            'archivo' => 'required|file|max:10240',
        ]);

        $path = $request->file('archivo')->store("empleados/{$empleado->id}/documentos", 'public');
        $empleado->documentos()->create([
            'tipo'    => $request->tipo,
            'nombre'  => $request->nombre,
            'archivo' => $path,
        ]);

        return back()->with('success', 'Documento adjuntado correctamente.');
    }

    public function destroyDocumento(EmpleadoDocumento $documento)
    {
        Storage::disk('public')->delete($documento->archivo);
        $documento->delete();
        return back()->with('success', 'Documento eliminado.');
    }

    private function generarCodigo(): string
    {
        $ultimo = Empleado::latest()->first();
        $numero  = $ultimo ? ((int) substr($ultimo->codigo_empleado, 3)) + 1 : 1;
        return 'EMP' . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }
}
