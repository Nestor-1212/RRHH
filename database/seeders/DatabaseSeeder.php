<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles del sistema
        $roles = ['Super Admin', 'Recursos Humanos', 'Jefe Departamento', 'Consulta'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Usuario Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@rrhh.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->syncRoles(['Super Admin']);

        // Usuario RRHH
        $rrhh = User::firstOrCreate(
            ['email' => 'rrhh@rrhh.com'],
            [
                'name'     => 'Jefa de RRHH',
                'password' => Hash::make('rrhh1234'),
            ]
        );
        $rrhh->syncRoles(['Recursos Humanos']);

        // Departamentos iniciales
        $departamentos = [
            ['nombre' => 'Gerencia General',       'descripcion' => 'Dirección y administración general de la empresa'],
            ['nombre' => 'Recursos Humanos',        'descripcion' => 'Gestión del capital humano'],
            ['nombre' => 'Contabilidad y Finanzas', 'descripcion' => 'Control financiero y contable'],
            ['nombre' => 'Tecnología',              'descripcion' => 'Infraestructura tecnológica y desarrollo de software'],
            ['nombre' => 'Ventas y Marketing',      'descripcion' => 'Estrategias comerciales y de marketing'],
            ['nombre' => 'Operaciones',             'descripcion' => 'Procesos operativos y logística'],
            ['nombre' => 'Servicio al Cliente',     'descripcion' => 'Atención y soporte a clientes'],
        ];

        foreach ($departamentos as $dep) {
            Departamento::firstOrCreate(['nombre' => $dep['nombre']], $dep);
        }

        $this->command->info('✅ Sistema RRHH — Seeder completado.');
        $this->command->info('   Super Admin: admin@rrhh.com / admin123');
        $this->command->info('   RRHH:        rrhh@rrhh.com / rrhh1234');
    }
}
