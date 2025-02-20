<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres', 
        'dni', 
        'celular', 
        'fecha_nacimiento', 
        'is_active'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }


    /**
     * Relación con la tabla de control de asistencia.
     */
    public function controlAsistencias()
    {
        return $this->hasMany(ControlAsistencia::class);
    }
}
