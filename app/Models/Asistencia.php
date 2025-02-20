<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = ['fecha_asistencia'];

    /**
     * Relación con la tabla de control de asistencia.
     */
    public function controlAsistencias()
    {
        return $this->hasMany(ControlAsistencia::class, 'asistencia_id');
    }
}

