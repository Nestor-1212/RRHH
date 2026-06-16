<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Models\AuditLog;
use App\Models\Bitacora;
use App\Models\Candidato;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\EmpleadoDocumento;
use App\Models\Salario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmpleadoController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Empleado::class);

        $query = Empleado::with('departamento')->select('empleados.*');

        if ($request->filled('buscar')) {
            $b = '%' . $request->buscar . '%';
            $query->where(fn($q) => $q
                ->where('nombre', 'like', $b)
                ->orWhere('apellido', 'like', $b)
                ->orWhere('cedula', 'like', $b)
                ->orWhere('codigo_empleado', 'like', $b)
                ->orWhere('email', 'like', $b)
            );
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('departamento_id')) {
            $query->where('departamento_id', $request->departamento_id);
        }

        $empleados     = $query->orderBy('apellido')->paginate(15)->withQueryString();
        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();

        return view('empleados.index', compact('empleados', 'departamentos'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Empleado::class);

        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        $jefes         = Empleado::where('estado', 'activo')->orderBy('apellido')->get();
        $candidato     = $request->filled('candidato_id')
            ? Candidato::findOrFail($request->candidato_id)
            : null;

        return view('empleados.create', compact('departamentos', 'jefes', 'candidato'));
    }

    public function store(StoreEmpleadoRequest $request): RedirectResponse
    {
        $empleado = DB::transaction(function () use ($request) {
            $data = $request->safe()->except('foto');
            $data['codigo_empleado'] = $this->generarCodigo();

            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('empleados/fotos', 'public');
            }

            $empleado = Empleado::create($data);

            Salario::create([
                'empleado_id'      => $empleado->id,
                'registrado_por'   => auth()->id(),
                'tipo'             => 'inicial',
                'monto'            => $data['salario'],
                'salario_anterior' => 0,
                'salario_nuevo'    => $data['salario'],
                'fecha'            => $data['fecha_ingreso'],
                'motivo'           => 'Salario inicial al ingreso.',
            ]);

            Bitacora::create([
                'empleado_id'     => $empleado->id,
                'candidato_id'    => $empleado->candidato_id,
                'fecha'           => $data['fecha_ingreso'],
                'tipo'            => 'contratacion',
                'descripcion'     => "Ingresó a la empresa. Cargo: {$empleado->cargo}. Departamento: {$empleado->departamento->nombre}.",
                'referencia_type' => Empleado::class,
                'referencia_id'   => $empleado->id,
            ]);

            if ($empleado->candidato_id) {
                Candidato::where('id', $empleado->candidato_id)
                    ->update(['estado' => 'contratado']);
            }

            AuditLog::record('create', $empleado, [], $empleado->getAttributes());

            return $empleado;
        });

        return redirect()->route('empleados.show', $empleado)
            ->with('success', "Empleado <strong>{$empleado->nombre_completo}</strong> registrado correctamente.");
    }

    public function show(Empleado $empleado): View
    {
        $this->authorize('view', $empleado);

        $empleado->load([
            'departamento',
            'jefe',
            'documentos',
            'salarios.registrador',
            'amonestaciones',
            'evaluaciones',
            'bitacoras',
            'salida',
        ]);

        return view('empleados.show', compact('empleado'));
    }

    public function edit(Empleado $empleado): View
    {
        $this->authorize('update', $empleado);

        $departamentos = Departamento::where('activo', true)->orderBy('nombre')->get();
        $jefes         = Empleado::where('estado', 'activo')
            ->where('id', '!=', $empleado->id)
            ->orderBy('apellido')
            ->get();

        return view('empleados.edit', compact('empleado', 'departamentos', 'jefes'));
    }

    public function update(UpdateEmpleadoRequest $request, Empleado $empleado): RedirectResponse
    {
        $data = $request->safe()->except('foto');

        if ($request->hasFile('foto')) {
            if ($empleado->foto) {
                Storage::disk('public')->delete($empleado->foto);
            }
            $data['foto'] = $request->file('foto')->store('empleados/fotos', 'public');
        }

        $old = $empleado->getAttributes();
        $empleado->update($data);
        AuditLog::record('update', $empleado, $old, $empleado->getChanges());

        return redirect()->route('empleados.show', $empleado)
            ->with('success', 'Expediente actualizado correctamente.');
    }

    public function storeDocumento(Request $request, Empleado $empleado): RedirectResponse
    {
        $this->authorize('manageDocuments', $empleado);

        $request->validate([
            'tipo'    => ['required', 'in:contrato,cedula,seguro_social,certificado,titulo,otro'],
            'nombre'  => ['required', 'string', 'max:200'],
            'archivo' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $path = $request->file('archivo')
            ->store("empleados/{$empleado->id}/documentos", 'public');

        $empleado->documentos()->create([
            'tipo'    => $request->tipo,
            'nombre'  => $request->nombre,
            'archivo' => $path,
        ]);

        return back()->with('success', 'Documento adjuntado correctamente.');
    }

    public function destroyDocumento(EmpleadoDocumento $documento): RedirectResponse
    {
        $this->authorize('manageDocuments', $documento->empleado);

        Storage::disk('public')->delete($documento->archivo);
        $documento->delete();

        return back()->with('success', 'Documento eliminado.');
    }

    private function generarCodigo(): string
    {
        // Lock for update prevents race conditions under concurrent inserts
        $ultimo = Empleado::lockForUpdate()->latest('id')->first();
        $numero = $ultimo
            ? ((int) ltrim(substr($ultimo->codigo_empleado, 3), '0') + 1)
            : 1;

        return 'EMP' . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }
}
