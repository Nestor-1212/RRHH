<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function vacantes()
    {
        return $this->hasMany(Vacante::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
