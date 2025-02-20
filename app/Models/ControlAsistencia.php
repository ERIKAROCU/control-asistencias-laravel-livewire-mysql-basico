<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAsistencia extends Model
{
    use HasFactory;

    protected $fillable = ['asistencia_id', 'empleado_id', 'estado', 'hora_entrada', 'hora_salida'];

    /**
     * Relación con la tabla de asistencia.
     */
    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }

    /**
     * Relación con la tabla de empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    protected $casts = [
        'fecha_asistencia' => 'date',
    ];

    /**
     * Verifica si un día de asistencia corresponde a un feriado.
     */
    public static function registrarAsistencia($fecha, $empleado_id, $estado, $hora_entrada = null, $hora_salida = null)
    {
        if (Feriado::esFeriado($fecha)) {
            return false;  // Si es feriado, no se registra la asistencia
        }

        // Si no es feriado, registrar la asistencia
        return self::create([
            'asistencia_id' => Asistencia::firstOrCreate(['fecha_asistencia' => $fecha])->id,
            'empleado_id' => $empleado_id,
            'estado' => $estado,
            'hora_entrada' => $hora_entrada,
            'hora_salida' => $hora_salida,
        ]);
    }
}
