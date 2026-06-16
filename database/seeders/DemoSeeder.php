<?php

namespace Database\Seeders;

use App\Models\Candidato;
use App\Models\CandidatoEstudio;
use App\Models\CandidatoExperiencia;
use App\Models\CandidatoIdioma;
use App\Models\CandidatoHabilidad;
use App\Models\CandidatoReferencia;
use App\Models\Vacante;
use App\Models\Entrevista;
use App\Models\Empleado;
use App\Models\Salario;
use App\Models\Asistencia;
use App\Models\Amonestacion;
use App\Models\Evaluacion;
use App\Models\Bitacora;
use App\Models\Salida;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@rrhh.com')->first();
        $rrhh  = User::where('email', 'rrhh@rrhh.com')->first();

        $deps = Departamento::pluck('id', 'nombre');

        // ─────────────────────────────────────────
        // 1. VACANTES
        // ─────────────────────────────────────────
        $vacantes = [
            [
                'nombre_puesto'   => 'Desarrollador Full Stack',
                'departamento_id' => $deps['Tecnología'],
                'descripcion'     => 'Desarrollo de aplicaciones web con Laravel y Vue.js. Mantenimiento de sistemas internos.',
                'requisitos'      => 'PHP 8+, Laravel, Vue.js, MySQL. 2 años de experiencia mínimo.',
                'salario_ofrecido'=> 1800.00,
                'tipo_contrato'   => 'indefinido',
                'fecha_apertura'  => '2026-03-01',
                'fecha_cierre'    => '2026-04-30',
                'estado'          => 'cerrada',
            ],
            [
                'nombre_puesto'   => 'Analista de Ventas',
                'departamento_id' => $deps['Ventas y Marketing'],
                'descripcion'     => 'Análisis de métricas de ventas, generación de reportes y seguimiento de KPIs comerciales.',
                'requisitos'      => 'Licenciatura en Administración o Mercadeo. Excel avanzado. 1 año de experiencia.',
                'salario_ofrecido'=> 1200.00,
                'tipo_contrato'   => 'indefinido',
                'fecha_apertura'  => '2026-04-15',
                'fecha_cierre'    => null,
                'estado'          => 'en_proceso',
            ],
            [
                'nombre_puesto'   => 'Asistente Contable',
                'departamento_id' => $deps['Contabilidad y Finanzas'],
                'descripcion'     => 'Registro contable, conciliaciones bancarias y apoyo en cierre mensual.',
                'requisitos'      => 'Estudiante o egresado de Contabilidad. Conocimiento de SAP es un plus.',
                'salario_ofrecido'=> 950.00,
                'tipo_contrato'   => 'definido',
                'fecha_apertura'  => '2026-05-01',
                'fecha_cierre'    => null,
                'estado'          => 'disponible',
            ],
            [
                'nombre_puesto'   => 'Coordinador de Operaciones',
                'departamento_id' => $deps['Operaciones'],
                'descripcion'     => 'Coordinación de logística, control de inventarios y gestión de proveedores.',
                'requisitos'      => 'Ingeniería Industrial o similar. 3 años de experiencia en logística.',
                'salario_ofrecido'=> 1600.00,
                'tipo_contrato'   => 'indefinido',
                'fecha_apertura'  => '2026-02-01',
                'fecha_cierre'    => '2026-03-15',
                'estado'          => 'cerrada',
            ],
        ];

        foreach ($vacantes as $v) {
            Vacante::create($v);
        }

        $vac = Vacante::pluck('id', 'nombre_puesto');

        // ─────────────────────────────────────────
        // 2. CANDIDATOS
        // ─────────────────────────────────────────
        $candidatosData = [
            // Contratado → se convirtió en empleado
            [
                'nombre' => 'Roberto', 'apellido' => 'Fuentes', 'cedula' => '0801-1990-00123',
                'fecha_nacimiento' => '1990-05-14', 'telefono' => '9988-1234',
                'email' => 'rfuentes@email.com', 'aspiracion_salarial' => 1800.00,
                'estado' => 'contratado', 'notas' => 'Excelente perfil técnico. Se incorporó al equipo de Tecnología.',
                'estudios' => [['nivel'=>'universitario','institucion'=>'UNAH','carrera'=>'Ingeniería en Sistemas','anio_inicio'=>2009,'anio_fin'=>2014,'graduado'=>true]],
                'experiencias' => [
                    ['empresa'=>'SoftTech SA','cargo'=>'Desarrollador Web','fecha_inicio'=>'2014-07-01','fecha_fin'=>'2019-12-31','descripcion'=>'Desarrollo con PHP y Laravel'],
                    ['empresa'=>'DigitalHN','cargo'=>'Full Stack Developer','fecha_inicio'=>'2020-01-01','fecha_fin'=>'2026-02-28','descripcion'=>'Vue.js + Laravel'],
                ],
                'idiomas' => [['idioma'=>'Español','nivel'=>'nativo'],['idioma'=>'Inglés','nivel'=>'avanzado']],
                'habilidades' => [['habilidad'=>'Laravel','nivel'=>'Avanzado'],['habilidad'=>'Vue.js','nivel'=>'Avanzado'],['habilidad'=>'MySQL','nivel'=>'Intermedio']],
                'referencias' => [['nombre'=>'Ing. Carlos Lima','relacion'=>'Jefe directo anterior','telefono'=>'9900-1111']],
            ],
            // En proceso
            [
                'nombre' => 'Sofía', 'apellido' => 'Martínez', 'cedula' => '0801-1995-00456',
                'fecha_nacimiento' => '1995-09-22', 'telefono' => '9977-5678',
                'email' => 'smartinez@email.com', 'aspiracion_salarial' => 1200.00,
                'estado' => 'en_proceso', 'notas' => 'Candidata sólida para Analista de Ventas. Pendiente entrevista final.',
                'estudios' => [['nivel'=>'universitario','institucion'=>'UNITEC','carrera'=>'Administración de Empresas','anio_inicio'=>2013,'anio_fin'=>2018,'graduado'=>true]],
                'experiencias' => [
                    ['empresa'=>'Comercial Rojas','cargo'=>'Ejecutiva de Ventas','fecha_inicio'=>'2018-06-01','fecha_fin'=>null,'actualmente'=>true,'descripcion'=>'Gestión de cartera de clientes, reportes de ventas'],
                ],
                'idiomas' => [['idioma'=>'Español','nivel'=>'nativo'],['idioma'=>'Inglés','nivel'=>'intermedio']],
                'habilidades' => [['habilidad'=>'Excel','nivel'=>'Avanzado'],['habilidad'=>'Power BI','nivel'=>'Intermedio'],['habilidad'=>'CRM Salesforce','nivel'=>'Básico']],
                'referencias' => [['nombre'=>'Lic. Patricia Soto','relacion'=>'Gerente Comercial','telefono'=>'9855-3333']],
            ],
            // Activo reciente
            [
                'nombre' => 'Diego', 'apellido' => 'Reyes', 'cedula' => '0801-1998-00789',
                'fecha_nacimiento' => '1998-03-10', 'telefono' => '9966-9012',
                'email' => 'dreyes@email.com', 'aspiracion_salarial' => 950.00,
                'estado' => 'activo', 'notas' => 'Recién graduado, buena actitud. Aplica para Asistente Contable.',
                'estudios' => [['nivel'=>'universitario','institucion'=>'UJCV','carrera'=>'Contaduría Pública','anio_inicio'=>2016,'anio_fin'=>2022,'graduado'=>true]],
                'experiencias' => [
                    ['empresa'=>'Despacho Contable ABC','cargo'=>'Auxiliar Contable','fecha_inicio'=>'2022-08-01','fecha_fin'=>'2025-12-31','descripcion'=>'Registro de transacciones, conciliaciones'],
                ],
                'idiomas' => [['idioma'=>'Español','nivel'=>'nativo']],
                'habilidades' => [['habilidad'=>'SAP','nivel'=>'Básico'],['habilidad'=>'Excel','nivel'=>'Intermedio']],
                'referencias' => [['nombre'=>'CPA Roberto Núñez','relacion'=>'Supervisor','telefono'=>'9744-2222']],
            ],
            // Descartado
            [
                'nombre' => 'Andrés', 'apellido' => 'Varela', 'cedula' => '0801-1988-00321',
                'fecha_nacimiento' => '1988-11-05', 'telefono' => '9933-4567',
                'email' => 'avarela@email.com', 'aspiracion_salarial' => 2500.00,
                'estado' => 'descartado', 'notas' => 'Aspiración salarial fuera del rango presupuestado.',
                'estudios' => [['nivel'=>'maestria','institucion'=>'ESPAE','carrera'=>'MBA','anio_inicio'=>2015,'anio_fin'=>2017,'graduado'=>true]],
                'experiencias' => [
                    ['empresa'=>'Corporación XYZ','cargo'=>'Gerente de Operaciones','fecha_inicio'=>'2017-03-01','fecha_fin'=>'2025-11-30','descripcion'=>'Gestión integral de operaciones'],
                ],
                'idiomas' => [['idioma'=>'Español','nivel'=>'nativo'],['idioma'=>'Inglés','nivel'=>'avanzado'],['idioma'=>'Portugués','nivel'=>'intermedio']],
                'habilidades' => [['habilidad'=>'Gestión de proyectos','nivel'=>'Avanzado'],['habilidad'=>'SAP','nivel'=>'Avanzado']],
                'referencias' => [['nombre'=>'Dr. Manuel Torres','relacion'=>'Socio de la firma','telefono'=>'9622-8888']],
            ],
        ];

        $candidatosCreados = [];
        foreach ($candidatosData as $cd) {
            $cand = Candidato::create([
                'nombre'              => $cd['nombre'],
                'apellido'            => $cd['apellido'],
                'cedula'              => $cd['cedula'],
                'fecha_nacimiento'    => $cd['fecha_nacimiento'],
                'telefono'            => $cd['telefono'],
                'email'               => $cd['email'],
                'aspiracion_salarial' => $cd['aspiracion_salarial'],
                'estado'              => $cd['estado'],
                'notas'               => $cd['notas'],
            ]);
            foreach ($cd['estudios'] as $e) {
                CandidatoEstudio::create(array_merge(['candidato_id' => $cand->id], $e));
            }
            foreach ($cd['experiencias'] as $e) {
                CandidatoExperiencia::create(array_merge(['candidato_id' => $cand->id, 'actualmente' => false], $e));
            }
            foreach ($cd['idiomas'] as $i) {
                CandidatoIdioma::create(array_merge(['candidato_id' => $cand->id], $i));
            }
            foreach ($cd['habilidades'] as $h) {
                CandidatoHabilidad::create(array_merge(['candidato_id' => $cand->id], $h));
            }
            foreach ($cd['referencias'] as $r) {
                CandidatoReferencia::create(array_merge(['candidato_id' => $cand->id, 'email' => null], $r));
            }
            $candidatosCreados[$cd['nombre'].' '.$cd['apellido']] = $cand;
        }

        // ─────────────────────────────────────────
        // 3. ENTREVISTAS
        // ─────────────────────────────────────────
        $entrevistaData = [
            // Roberto Fuentes: 2 entrevistas exitosas → contratado
            [
                'candidato_id'     => $candidatosCreados['Roberto Fuentes']->id,
                'vacante_id'       => $vac['Desarrollador Full Stack'],
                'entrevistador_id' => $admin->id,
                'fecha_entrevista' => '2026-03-10 10:00:00',
                'tipo'             => 'inicial',
                'puntaje_experiencia'   => 19, 'puntaje_conocimiento'  => 18,
                'puntaje_comunicacion'  => 17, 'puntaje_actitud'       => 20,
                'puntaje_disponibilidad'=> 20, 'puntaje_total'         => 94,
                'comentarios'   => 'Excelente candidato. Domina el stack tecnológico requerido.',
                'fortalezas'    => 'Amplia experiencia en Laravel y Vue.js. Buen comunicador.',
                'debilidades'   => 'Poca experiencia en proyectos de gran escala.',
                'resultado'     => 'seleccionado',
            ],
            [
                'candidato_id'     => $candidatosCreados['Roberto Fuentes']->id,
                'vacante_id'       => $vac['Desarrollador Full Stack'],
                'entrevistador_id' => $rrhh->id,
                'fecha_entrevista' => '2026-03-17 09:00:00',
                'tipo'             => 'final',
                'puntaje_experiencia'   => 20, 'puntaje_conocimiento'  => 19,
                'puntaje_comunicacion'  => 18, 'puntaje_actitud'       => 20,
                'puntaje_disponibilidad'=> 20, 'puntaje_total'         => 97,
                'comentarios'   => 'Confirmamos la selección. Fecha de inicio acordada para el 1 de abril.',
                'fortalezas'    => 'Perfil muy completo. Disponibilidad inmediata.',
                'debilidades'   => 'Ninguna relevante.',
                'resultado'     => 'seleccionado',
            ],
            // Sofía Martínez: 1 entrevista inicial → seleccionada, pendiente final
            [
                'candidato_id'     => $candidatosCreados['Sofía Martínez']->id,
                'vacante_id'       => $vac['Analista de Ventas'],
                'entrevistador_id' => $rrhh->id,
                'fecha_entrevista' => '2026-05-05 14:00:00',
                'tipo'             => 'inicial',
                'puntaje_experiencia'   => 16, 'puntaje_conocimiento'  => 15,
                'puntaje_comunicacion'  => 18, 'puntaje_actitud'       => 19,
                'puntaje_disponibilidad'=> 18, 'puntaje_total'         => 86,
                'comentarios'   => 'Muy buena candidata. Pasa a entrevista técnica.',
                'fortalezas'    => 'Excelente presentación personal y comunicación.',
                'debilidades'   => 'Necesita reforzar conocimiento en análisis de datos.',
                'resultado'     => 'seleccionado',
            ],
            // Andrés Varela: descartado por pretensión salarial
            [
                'candidato_id'     => $candidatosCreados['Andrés Varela']->id,
                'vacante_id'       => $vac['Coordinador de Operaciones'],
                'entrevistador_id' => $admin->id,
                'fecha_entrevista' => '2026-02-10 11:00:00',
                'tipo'             => 'inicial',
                'puntaje_experiencia'   => 20, 'puntaje_conocimiento'  => 19,
                'puntaje_comunicacion'  => 18, 'puntaje_actitud'       => 17,
                'puntaje_disponibilidad'=> 10, 'puntaje_total'         => 84,
                'comentarios'   => 'Perfil muy bueno pero aspiración salarial supera el presupuesto en un 56%.',
                'fortalezas'    => 'Gran experiencia en coordinación logística.',
                'debilidades'   => 'Pretensión salarial muy elevada para el puesto.',
                'resultado'     => 'no_seleccionado',
                'motivo_rechazo'  => 'pretension_salarial',
                'detalle_rechazo' => 'El candidato solicita $2,500 pero el presupuesto aprobado es $1,600.',
            ],
        ];

        foreach ($entrevistaData as $e) {
            Entrevista::create(array_merge([
                'motivo_rechazo'  => null,
                'detalle_rechazo' => null,
            ], $e));
        }

        // ─────────────────────────────────────────
        // 4. EMPLEADOS (12 activos + 1 retirado)
        // ─────────────────────────────────────────
        $empleadosData = [
            // Gerencia General
            ['codigo'=>'EMP-001','nombre'=>'Laura','apellido'=>'Castellanos','cedula'=>'0801-1978-10001',
             'fecha_nacimiento'=>'1978-03-20','telefono'=>'9911-0001','email'=>'l.castellanos@empresa.com',
             'contacto_emergencia'=>'Jorge Castellanos','telefono_emergencia'=>'9900-0001',
             'fecha_ingreso'=>'2019-01-02','cargo'=>'Gerente General',
             'departamento_id'=>$deps['Gerencia General'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>4500.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // RRHH
            ['codigo'=>'EMP-002','nombre'=>'Patricia','apellido'=>'Morales','cedula'=>'0801-1985-10002',
             'fecha_nacimiento'=>'1985-07-14','telefono'=>'9911-0002','email'=>'p.morales@empresa.com',
             'contacto_emergencia'=>'Luis Morales','telefono_emergencia'=>'9900-0002',
             'fecha_ingreso'=>'2020-03-01','cargo'=>'Jefa de Recursos Humanos',
             'departamento_id'=>$deps['Recursos Humanos'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>2800.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            ['codigo'=>'EMP-003','nombre'=>'Carmen','apellido'=>'Rodríguez','cedula'=>'0801-1992-10003',
             'fecha_nacimiento'=>'1992-11-30','telefono'=>'9911-0003','email'=>'c.rodriguez@empresa.com',
             'contacto_emergencia'=>'Ana Rodríguez','telefono_emergencia'=>'9900-0003',
             'fecha_ingreso'=>'2021-06-01','cargo'=>'Asistente de RRHH',
             'departamento_id'=>$deps['Recursos Humanos'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>1100.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // Contabilidad
            ['codigo'=>'EMP-004','nombre'=>'Fernando','apellido'=>'Aguilar','cedula'=>'0801-1980-10004',
             'fecha_nacimiento'=>'1980-04-25','telefono'=>'9911-0004','email'=>'f.aguilar@empresa.com',
             'contacto_emergencia'=>'Rosa Aguilar','telefono_emergencia'=>'9900-0004',
             'fecha_ingreso'=>'2018-09-01','cargo'=>'Contador General',
             'departamento_id'=>$deps['Contabilidad y Finanzas'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>2500.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            ['codigo'=>'EMP-005','nombre'=>'Valeria','apellido'=>'Nuñez','cedula'=>'0801-1994-10005',
             'fecha_nacimiento'=>'1994-08-12','telefono'=>'9911-0005','email'=>'v.nunez@empresa.com',
             'contacto_emergencia'=>'Mario Nuñez','telefono_emergencia'=>'9900-0005',
             'fecha_ingreso'=>'2022-02-01','cargo'=>'Auxiliar Contable',
             'departamento_id'=>$deps['Contabilidad y Finanzas'],'jefe_id'=>null,
             'tipo_contrato'=>'definido','salario'=>900.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // Tecnología
            ['codigo'=>'EMP-006','nombre'=>'Marcos','apellido'=>'Espinoza','cedula'=>'0801-1987-10006',
             'fecha_nacimiento'=>'1987-02-18','telefono'=>'9911-0006','email'=>'m.espinoza@empresa.com',
             'contacto_emergencia'=>'Elena Espinoza','telefono_emergencia'=>'9900-0006',
             'fecha_ingreso'=>'2019-05-01','cargo'=>'Jefe de Tecnología',
             'departamento_id'=>$deps['Tecnología'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>3200.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // Roberto Fuentes → candidato contratado
            ['codigo'=>'EMP-007','nombre'=>'Roberto','apellido'=>'Fuentes','cedula'=>'0801-1990-00123',
             'fecha_nacimiento'=>'1990-05-14','telefono'=>'9988-1234','email'=>'r.fuentes@empresa.com',
             'contacto_emergencia'=>'María Fuentes','telefono_emergencia'=>'9900-0007',
             'fecha_ingreso'=>'2026-04-01','cargo'=>'Desarrollador Full Stack',
             'departamento_id'=>$deps['Tecnología'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>1800.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo',
             'candidato_id' => $candidatosCreados['Roberto Fuentes']->id],

            // Ventas
            ['codigo'=>'EMP-008','nombre'=>'Gloria','apellido'=>'Pineda','cedula'=>'0801-1983-10008',
             'fecha_nacimiento'=>'1983-12-03','telefono'=>'9911-0008','email'=>'g.pineda@empresa.com',
             'contacto_emergencia'=>'Pedro Pineda','telefono_emergencia'=>'9900-0008',
             'fecha_ingreso'=>'2020-07-01','cargo'=>'Gerente de Ventas',
             'departamento_id'=>$deps['Ventas y Marketing'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>2900.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            ['codigo'=>'EMP-009','nombre'=>'Héctor','apellido'=>'Bautista','cedula'=>'0801-1996-10009',
             'fecha_nacimiento'=>'1996-06-21','telefono'=>'9911-0009','email'=>'h.bautista@empresa.com',
             'contacto_emergencia'=>'Clara Bautista','telefono_emergencia'=>'9900-0009',
             'fecha_ingreso'=>'2023-01-09','cargo'=>'Ejecutivo de Ventas',
             'departamento_id'=>$deps['Ventas y Marketing'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>1000.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // Operaciones
            ['codigo'=>'EMP-010','nombre'=>'Isabel','apellido'=>'Sánchez','cedula'=>'0801-1981-10010',
             'fecha_nacimiento'=>'1981-09-08','telefono'=>'9911-0010','email'=>'i.sanchez@empresa.com',
             'contacto_emergencia'=>'Luis Sánchez','telefono_emergencia'=>'9900-0010',
             'fecha_ingreso'=>'2017-11-01','cargo'=>'Coordinadora de Operaciones',
             'departamento_id'=>$deps['Operaciones'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>2200.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // Servicio al Cliente
            ['codigo'=>'EMP-011','nombre'=>'Daniela','apellido'=>'Flores','cedula'=>'0801-1997-10011',
             'fecha_nacimiento'=>'1997-04-15','telefono'=>'9911-0011','email'=>'d.flores@empresa.com',
             'contacto_emergencia'=>'Marta Flores','telefono_emergencia'=>'9900-0011',
             'fecha_ingreso'=>'2024-03-01','cargo'=>'Agente de Servicio al Cliente',
             'departamento_id'=>$deps['Servicio al Cliente'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>850.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'activo'],

            // Empleado que se retira (salida)
            ['codigo'=>'EMP-012','nombre'=>'Carlos','apellido'=>'Mendoza','cedula'=>'0801-1986-10012',
             'fecha_nacimiento'=>'1986-01-22','telefono'=>'9911-0012','email'=>'c.mendoza@empresa.com',
             'contacto_emergencia'=>'Luz Mendoza','telefono_emergencia'=>'9900-0012',
             'fecha_ingreso'=>'2020-04-01','cargo'=>'Analista de Sistemas',
             'departamento_id'=>$deps['Tecnología'],'jefe_id'=>null,
             'tipo_contrato'=>'indefinido','salario'=>1500.00,'horario'=>'Lun-Vie 8:00-17:00',
             'jornada'=>'completa','estado'=>'retirado'],
        ];

        $empleados = [];
        foreach ($empleadosData as $ed) {
            $data = [
                'codigo_empleado'      => $ed['codigo'],
                'nombre'               => $ed['nombre'],
                'apellido'             => $ed['apellido'],
                'cedula'               => $ed['cedula'],
                'fecha_nacimiento'     => $ed['fecha_nacimiento'],
                'telefono'             => $ed['telefono'],
                'email'                => $ed['email'],
                'contacto_emergencia'  => $ed['contacto_emergencia'],
                'telefono_emergencia'  => $ed['telefono_emergencia'],
                'fecha_ingreso'        => $ed['fecha_ingreso'],
                'cargo'                => $ed['cargo'],
                'departamento_id'      => $ed['departamento_id'],
                'jefe_id'              => $ed['jefe_id'],
                'tipo_contrato'        => $ed['tipo_contrato'],
                'salario'              => $ed['salario'],
                'horario'              => $ed['horario'],
                'jornada'              => $ed['jornada'],
                'estado'               => $ed['estado'],
                'candidato_id'         => $ed['candidato_id'] ?? null,
                'direccion'            => 'Tegucigalpa, Honduras',
            ];
            $empleados[$ed['codigo']] = Empleado::create($data);
        }

        // Asignar jefes
        $empleados['EMP-003']->update(['jefe_id' => $empleados['EMP-002']->id]);
        $empleados['EMP-005']->update(['jefe_id' => $empleados['EMP-004']->id]);
        $empleados['EMP-007']->update(['jefe_id' => $empleados['EMP-006']->id]);
        $empleados['EMP-009']->update(['jefe_id' => $empleados['EMP-008']->id]);

        // ─────────────────────────────────────────
        // 5. SALARIOS (historial)
        // ─────────────────────────────────────────
        $salariosCambios = [
            // EMP-001 Laura Castellanos – aumento por antigüedad
            ['emp'=>'EMP-001','tipo'=>'inicial','monto'=>4000.00,'sal_ant'=>null,'sal_nuevo'=>4000.00,'fecha'=>'2019-01-02','motivo'=>'Salario inicial al ingreso'],
            ['emp'=>'EMP-001','tipo'=>'aumento','monto'=>500.00,'sal_ant'=>4000.00,'sal_nuevo'=>4500.00,'fecha'=>'2022-01-01','motivo'=>'Aumento por desempeño sobresaliente y antigüedad'],

            // EMP-002 Patricia Morales
            ['emp'=>'EMP-002','tipo'=>'inicial','monto'=>2500.00,'sal_ant'=>null,'sal_nuevo'=>2500.00,'fecha'=>'2020-03-01','motivo'=>'Salario inicial'],
            ['emp'=>'EMP-002','tipo'=>'aumento','monto'=>300.00,'sal_ant'=>2500.00,'sal_nuevo'=>2800.00,'fecha'=>'2023-03-01','motivo'=>'Aumento por promoción a Jefa de RRHH'],

            // EMP-003 Carmen Rodríguez
            ['emp'=>'EMP-003','tipo'=>'inicial','monto'=>950.00,'sal_ant'=>null,'sal_nuevo'=>950.00,'fecha'=>'2021-06-01','motivo'=>'Salario inicial'],
            ['emp'=>'EMP-003','tipo'=>'aumento','monto'=>150.00,'sal_ant'=>950.00,'sal_nuevo'=>1100.00,'fecha'=>'2024-06-01','motivo'=>'Aumento anual por buen desempeño'],

            // EMP-006 Marcos Espinoza – aumento significativo
            ['emp'=>'EMP-006','tipo'=>'inicial','monto'=>2800.00,'sal_ant'=>null,'sal_nuevo'=>2800.00,'fecha'=>'2019-05-01','motivo'=>'Salario inicial'],
            ['emp'=>'EMP-006','tipo'=>'aumento','monto'=>400.00,'sal_ant'=>2800.00,'sal_nuevo'=>3200.00,'fecha'=>'2024-01-01','motivo'=>'Ascenso a Jefe de Tecnología'],

            // EMP-007 Roberto Fuentes – nuevo
            ['emp'=>'EMP-007','tipo'=>'inicial','monto'=>1800.00,'sal_ant'=>null,'sal_nuevo'=>1800.00,'fecha'=>'2026-04-01','motivo'=>'Salario inicial acordado en oferta laboral'],

            // EMP-009 Héctor Bautista – descuento por préstamo
            ['emp'=>'EMP-009','tipo'=>'inicial','monto'=>1000.00,'sal_ant'=>null,'sal_nuevo'=>1000.00,'fecha'=>'2023-01-09','motivo'=>'Salario inicial'],
            ['emp'=>'EMP-009','tipo'=>'bonificacion','monto'=>200.00,'sal_ant'=>1000.00,'sal_nuevo'=>1200.00,'fecha'=>'2025-07-01','motivo'=>'Bono por cumplimiento de meta de ventas Q2 2025'],
            ['emp'=>'EMP-009','tipo'=>'descuento','monto'=>-200.00,'sal_ant'=>1200.00,'sal_nuevo'=>1000.00,'fecha'=>'2026-01-01','motivo'=>'Descuento mensual por préstamo personal empresa'],

            // EMP-012 Carlos Mendoza (retirado) – historial completo
            ['emp'=>'EMP-012','tipo'=>'inicial','monto'=>1300.00,'sal_ant'=>null,'sal_nuevo'=>1300.00,'fecha'=>'2020-04-01','motivo'=>'Salario inicial'],
            ['emp'=>'EMP-012','tipo'=>'aumento','monto'=>200.00,'sal_ant'=>1300.00,'sal_nuevo'=>1500.00,'fecha'=>'2023-04-01','motivo'=>'Aumento por antigüedad y buen desempeño'],
        ];

        foreach ($salariosCambios as $s) {
            Salario::create([
                'empleado_id'     => $empleados[$s['emp']]->id,
                'registrado_por'  => $rrhh->id,
                'tipo'            => $s['tipo'],
                'monto'           => abs($s['monto']),
                'salario_anterior'=> $s['sal_ant'],
                'salario_nuevo'   => $s['sal_nuevo'],
                'fecha'           => $s['fecha'],
                'motivo'          => $s['motivo'],
            ]);
        }

        // ─────────────────────────────────────────
        // 6. ASISTENCIAS (últimos 20 días laborales)
        // ─────────────────────────────────────────
        $fechasLaborales = [];
        $d = new \DateTime('2026-05-18');
        while (count($fechasLaborales) < 20) {
            $dow = (int)$d->format('N');
            if ($dow < 6) {
                $fechasLaborales[] = $d->format('Y-m-d');
            }
            $d->modify('+1 day');
        }

        // Empleados activos para asistencia (excluye el retirado EMP-012)
        $empsActivos = ['EMP-001','EMP-002','EMP-003','EMP-004','EMP-005',
                        'EMP-006','EMP-007','EMP-008','EMP-009','EMP-010','EMP-011'];

        // Patrones de asistencia por empleado
        $patrones = [
            'EMP-001' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-002' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','permiso','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-003' => ['normal','normal','tardanza','normal','normal','normal','normal','normal','normal','normal','normal','normal','tardanza','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-004' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-005' => ['normal','tardanza','normal','normal','ausencia','normal','normal','normal','tardanza','normal','normal','normal','normal','normal','normal','normal','tardanza','normal','normal','normal'],
            'EMP-006' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-007' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-008' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','vacacion','vacacion','vacacion','normal','normal','normal'],
            'EMP-009' => ['normal','normal','tardanza','normal','normal','ausencia','normal','normal','normal','normal','tardanza','normal','ausencia','normal','normal','normal','tardanza','normal','normal','normal'],
            'EMP-010' => ['normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal','normal'],
            'EMP-011' => ['normal','normal','normal','tardanza','normal','normal','normal','normal','normal','normal','normal','permiso','normal','normal','normal','normal','normal','normal','tardanza','normal'],
        ];

        foreach ($empsActivos as $codigo) {
            $patron = $patrones[$codigo];
            foreach ($fechasLaborales as $i => $fecha) {
                $tipo = $patron[$i] ?? 'normal';
                $obs  = null;
                $entrada = null;
                $salida  = null;

                switch ($tipo) {
                    case 'normal':
                        $entrada = '08:00:00'; $salida = '17:00:00'; break;
                    case 'tardanza':
                        $min = [15,20,25,30,35][array_rand([15,20,25,30,35])];
                        $entrada = "08:{$min}:00"; $salida = '17:00:00';
                        $obs = "Llegada con {$min} minutos de retraso."; break;
                    case 'ausencia':
                        $obs = 'Ausencia no justificada.'; break;
                    case 'permiso':
                        $obs = 'Permiso autorizado por RRHH.'; break;
                    case 'vacacion':
                        $obs = 'Período de vacaciones aprobado.'; break;
                }

                Asistencia::create([
                    'empleado_id'   => $empleados[$codigo]->id,
                    'fecha'         => $fecha,
                    'hora_entrada'  => $entrada,
                    'hora_salida'   => $salida,
                    'tipo'          => $tipo,
                    'observaciones' => $obs,
                    'registrado_por'=> $rrhh->id,
                ]);
            }
        }

        // ─────────────────────────────────────────
        // 7. AMONESTACIONES
        // ─────────────────────────────────────────
        $amonestaciones = [
            // EMP-005 Valeria Nuñez – llamado de atención por tardanzas
            [
                'emp' => 'EMP-005',
                'fecha' => '2026-04-10',
                'tipo' => 'llamado_atencion',
                'motivo' => 'Acumulación de tardanzas',
                'descripcion' => 'Se han registrado 4 tardanzas consecutivas en el mes de marzo 2026 sin justificación válida. Se le hace un llamado de atención formal conforme al reglamento interno.',
            ],
            // EMP-009 Héctor Bautista – amonestación escrita por ausencia
            [
                'emp' => 'EMP-009',
                'fecha' => '2026-05-08',
                'tipo' => 'escrita',
                'motivo' => 'Ausencias injustificadas repetidas',
                'descripcion' => 'El colaborador ha faltado 3 veces sin aviso previo ni justificación médica durante los meses de abril y mayo 2026. Se emite amonestación escrita como segundo llamado de atención. De repetirse la situación se procederá con suspensión.',
            ],
            // EMP-012 Carlos Mendoza (retirado) – en su historial
            [
                'emp' => 'EMP-012',
                'fecha' => '2025-09-15',
                'tipo' => 'verbal',
                'motivo' => 'Incumplimiento de procedimientos de seguridad',
                'descripcion' => 'El colaborador omitió el procedimiento de respaldo de datos antes de realizar mantenimiento al servidor. Se le instruye verbalmente sobre la importancia de seguir los protocolos establecidos.',
            ],
        ];

        foreach ($amonestaciones as $a) {
            Amonestacion::create([
                'empleado_id'    => $empleados[$a['emp']]->id,
                'registrado_por' => $rrhh->id,
                'fecha'          => $a['fecha'],
                'tipo'           => $a['tipo'],
                'motivo'         => $a['motivo'],
                'descripcion'    => $a['descripcion'],
                'dias_suspension'=> 0,
            ]);
        }

        // ─────────────────────────────────────────
        // 8. EVALUACIONES DE DESEMPEÑO
        // ─────────────────────────────────────────
        $evaluacionesData = [
            // Semestre 1 2025 – varios empleados
            ['emp'=>'EMP-001','per'=>'Semestre I 2025','prod'=>19,'resp'=>20,'eq'=>20,'cal'=>19,'cum'=>20,'total'=>98,'calif'=>'excelente','coment'=>'Liderazgo excepcional. Cumplió todos los objetivos estratégicos del semestre.'],
            ['emp'=>'EMP-002','per'=>'Semestre I 2025','prod'=>18,'resp'=>19,'eq'=>18,'cal'=>18,'cum'=>19,'total'=>92,'calif'=>'excelente','coment'=>'Excelente gestión del capital humano. Implementó nuevos procesos de reclutamiento.'],
            ['emp'=>'EMP-003','per'=>'Semestre I 2025','prod'=>15,'resp'=>16,'eq'=>17,'cal'=>15,'cum'=>16,'total'=>79,'calif'=>'bueno','coment'=>'Buen desempeño general. Debe mejorar la gestión del tiempo.'],
            ['emp'=>'EMP-004','per'=>'Semestre I 2025','prod'=>18,'resp'=>19,'eq'=>16,'cal'=>19,'cum'=>18,'total'=>90,'calif'=>'excelente','coment'=>'Cierre contable dentro del plazo. Cero errores en auditoría interna.'],
            ['emp'=>'EMP-005','per'=>'Semestre I 2025','prod'=>13,'resp'=>12,'eq'=>15,'cal'=>13,'cum'=>12,'total'=>65,'calif'=>'regular','coment'=>'Desempeño por debajo de lo esperado. Puntualidad y responsabilidad deben mejorar urgentemente.'],
            ['emp'=>'EMP-006','per'=>'Semestre I 2025','prod'=>20,'resp'=>19,'eq'=>18,'cal'=>20,'cum'=>19,'total'=>96,'calif'=>'excelente','coment'=>'Implementación exitosa del nuevo sistema de gestión. Equipo bien coordinado.'],
            ['emp'=>'EMP-008','per'=>'Semestre I 2025','prod'=>17,'resp'=>18,'eq'=>16,'cal'=>17,'cum'=>18,'total'=>86,'calif'=>'bueno','coment'=>'Cumplimiento de metas de ventas al 95%. Buen manejo del equipo comercial.'],
            ['emp'=>'EMP-009','per'=>'Semestre I 2025','prod'=>11,'resp'=>10,'eq'=>13,'cal'=>12,'cum'=>11,'total'=>57,'calif'=>'deficiente','coment'=>'Cumplimiento de metas al 60%. Ausentismo frecuente afecta el rendimiento del equipo. Se recomienda plan de mejora.'],
            ['emp'=>'EMP-010','per'=>'Semestre I 2025','prod'=>17,'resp'=>18,'eq'=>19,'cal'=>17,'cum'=>18,'total'=>89,'calif'=>'bueno','coment'=>'Excelente coordinación logística. Reducción del 15% en tiempos de entrega.'],

            // Semestre 2 2025
            ['emp'=>'EMP-001','per'=>'Semestre II 2025','prod'=>20,'resp'=>20,'eq'=>20,'cal'=>20,'cum'=>20,'total'=>100,'calif'=>'excelente','coment'=>'Puntuación perfecta. Lideró la expansión a 2 nuevas líneas de negocio.'],
            ['emp'=>'EMP-002','per'=>'Semestre II 2025','prod'=>19,'resp'=>19,'eq'=>19,'cal'=>18,'cum'=>19,'total'=>94,'calif'=>'excelente','coment'=>'Proceso de reestructuración de RRHH completado exitosamente.'],
            ['emp'=>'EMP-006','per'=>'Semestre II 2025','prod'=>19,'resp'=>20,'eq'=>18,'cal'=>19,'cum'=>20,'total'=>96,'calif'=>'excelente','coment'=>'Migración de sistemas completada sin interrupciones. Proyecto entregado a tiempo.'],
            ['emp'=>'EMP-009','per'=>'Semestre II 2025','prod'=>14,'resp'=>13,'eq'=>14,'cal'=>13,'cum'=>14,'total'=>68,'calif'=>'regular','coment'=>'Mejora leve con respecto al semestre anterior. Aún no alcanza el nivel esperado. Continúa plan de mejora.'],
        ];

        foreach ($evaluacionesData as $ev) {
            Evaluacion::create([
                'empleado_id'           => $empleados[$ev['emp']]->id,
                'evaluador_id'          => $rrhh->id,
                'periodo'               => $ev['per'],
                'puntaje_productividad' => $ev['prod'],
                'puntaje_responsabilidad'=> $ev['resp'],
                'puntaje_trabajo_equipo'=> $ev['eq'],
                'puntaje_calidad'       => $ev['cal'],
                'puntaje_cumplimiento'  => $ev['cum'],
                'puntaje_total'         => $ev['total'],
                'calificacion'          => $ev['calif'],
                'comentarios'           => $ev['coment'],
            ]);
        }

        // ─────────────────────────────────────────
        // 9. BITÁCORA (timeline de eventos)
        // ─────────────────────────────────────────
        $bitacoras = [
            ['emp'=>'EMP-001','fecha'=>'2019-01-02','tipo'=>'contratacion','desc'=>'Ingreso a la empresa como Gerente General.'],
            ['emp'=>'EMP-001','fecha'=>'2022-01-01','tipo'=>'salario','desc'=>'Aumento salarial de $4,000 a $4,500 por desempeño y antigüedad.'],
            ['emp'=>'EMP-002','fecha'=>'2020-03-01','tipo'=>'contratacion','desc'=>'Ingreso a la empresa como Analista de RRHH.'],
            ['emp'=>'EMP-002','fecha'=>'2023-03-01','tipo'=>'promocion','desc'=>'Promoción a Jefa de Recursos Humanos. Aumento de $2,500 a $2,800.'],
            ['emp'=>'EMP-004','fecha'=>'2018-09-01','tipo'=>'contratacion','desc'=>'Ingreso como Auxiliar Contable.'],
            ['emp'=>'EMP-004','fecha'=>'2021-01-01','tipo'=>'promocion','desc'=>'Ascenso a Contador General tras jubilación del titular.'],
            ['emp'=>'EMP-005','fecha'=>'2022-02-01','tipo'=>'contratacion','desc'=>'Ingreso como Auxiliar Contable bajo contrato a término definido.'],
            ['emp'=>'EMP-005','fecha'=>'2026-04-10','tipo'=>'amonestacion','desc'=>'Llamado de atención formal por acumulación de tardanzas en marzo 2026.'],
            ['emp'=>'EMP-006','fecha'=>'2019-05-01','tipo'=>'contratacion','desc'=>'Ingreso como Analista de Sistemas Senior.'],
            ['emp'=>'EMP-006','fecha'=>'2024-01-01','tipo'=>'promocion','desc'=>'Ascenso a Jefe de Tecnología. Aumento de $2,800 a $3,200.'],
            ['emp'=>'EMP-007','fecha'=>'2026-04-01','tipo'=>'contratacion','desc'=>'Contratación como Desarrollador Full Stack. Proviene del proceso de reclutamiento de marzo 2026.'],
            ['emp'=>'EMP-009','fecha'=>'2023-01-09','tipo'=>'contratacion','desc'=>'Ingreso como Ejecutivo de Ventas.'],
            ['emp'=>'EMP-009','fecha'=>'2025-07-01','tipo'=>'bono','desc'=>'Bono de $200 por cumplimiento de meta de ventas Q2 2025.'],
            ['emp'=>'EMP-009','fecha'=>'2026-05-08','tipo'=>'amonestacion','desc'=>'Amonestación escrita por ausencias injustificadas repetidas.'],
            ['emp'=>'EMP-012','fecha'=>'2020-04-01','tipo'=>'contratacion','desc'=>'Ingreso como Analista de Sistemas.'],
            ['emp'=>'EMP-012','fecha'=>'2023-04-01','tipo'=>'salario','desc'=>'Aumento salarial de $1,300 a $1,500 por antigüedad.'],
            ['emp'=>'EMP-012','fecha'=>'2025-09-15','tipo'=>'amonestacion','desc'=>'Amonestación verbal por incumplimiento de procedimientos de seguridad.'],
            ['emp'=>'EMP-012','fecha'=>'2026-06-01','tipo'=>'salida','desc'=>'Renuncia voluntaria aceptada. Último día de labores: 1 de junio 2026.'],
        ];

        foreach ($bitacoras as $b) {
            Bitacora::create([
                'empleado_id' => $empleados[$b['emp']]->id,
                'candidato_id'=> null,
                'fecha'       => $b['fecha'],
                'tipo'        => $b['tipo'],
                'descripcion' => $b['desc'],
            ]);
        }

        // Bitácoras de candidatos
        Bitacora::create([
            'candidato_id' => $candidatosCreados['Roberto Fuentes']->id,
            'empleado_id'  => null,
            'fecha'        => '2026-03-01',
            'tipo'         => 'postulacion',
            'descripcion'  => 'Postulación recibida para la vacante Desarrollador Full Stack.',
        ]);
        Bitacora::create([
            'candidato_id' => $candidatosCreados['Roberto Fuentes']->id,
            'empleado_id'  => null,
            'fecha'        => '2026-03-10',
            'tipo'         => 'entrevista',
            'descripcion'  => 'Entrevista inicial realizada. Puntaje: 94/100. Resultado: seleccionado.',
        ]);
        Bitacora::create([
            'candidato_id' => $candidatosCreados['Roberto Fuentes']->id,
            'empleado_id'  => null,
            'fecha'        => '2026-03-17',
            'tipo'         => 'entrevista',
            'descripcion'  => 'Entrevista final realizada. Puntaje: 97/100. Oferta de trabajo extendida.',
        ]);
        Bitacora::create([
            'candidato_id' => $candidatosCreados['Roberto Fuentes']->id,
            'empleado_id'  => null,
            'fecha'        => '2026-04-01',
            'tipo'         => 'contratacion',
            'descripcion'  => 'Candidato contratado. Código de empleado: EMP-007.',
        ]);

        // ─────────────────────────────────────────
        // 10. SALIDA (Carlos Mendoza)
        // ─────────────────────────────────────────
        Salida::create([
            'empleado_id'   => $empleados['EMP-012']->id,
            'registrado_por'=> $rrhh->id,
            'fecha_salida'  => '2026-06-01',
            'tipo'          => 'renuncia',
            'ultimo_cargo'  => 'Analista de Sistemas',
            'ultimo_salario'=> 1500.00,
            'motivo'        => 'El colaborador presentó carta de renuncia voluntaria con 30 días de anticipación el día 1 de mayo de 2026, alegando una oportunidad laboral en el extranjero. Se realizó proceso de empalme con el equipo de Tecnología. Salida en buenos términos.',
        ]);

        $this->command->info('');
        $this->command->info('✅  DemoSeeder completado exitosamente');
        $this->command->info('   → 4  vacantes creadas');
        $this->command->info('   → 4  candidatos con perfil completo');
        $this->command->info('   → 4  entrevistas con puntajes');
        $this->command->info('   → 12 empleados (11 activos + 1 retirado)');
        $this->command->info('   → 14 registros de historial salarial');
        $this->command->info('   → 220 registros de asistencia (20 días × 11 empleados)');
        $this->command->info('   → 3  amonestaciones');
        $this->command->info('   → 13 evaluaciones de desempeño');
        $this->command->info('   → 22 entradas en bitácora');
        $this->command->info('   → 1  salida registrada (renuncia voluntaria)');
        $this->command->info('');
    }
}
