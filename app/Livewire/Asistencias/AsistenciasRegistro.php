<?php

namespace App\Livewire\Asistencias;

use App\Models\Asistencia;
use App\Models\ControlAsistencia;
use App\Models\Empleado;
use App\Models\Feriado; // Importar el modelo Feriado
use Livewire\Component;

class AsistenciasRegistro extends Component
{
    public $dni;

    protected $rules = [
        'dni' => 'required|exists:empleados,dni',
    ];

    public function submit()
    {
        $validatedData = $this->validate();

        // Obtener la fecha actual
        $fecha = now()->format('Y-m-d');

        // Verificar si la fecha actual es un feriado
        $esFeriado = Feriado::where('fecha', $fecha)->exists();

        if ($esFeriado) {
            session()->flash('error', 'Hoy es feriado. No se pueden registrar asistencias.');
            return; // Detener la ejecución
        }

        // Buscar al empleado por su DNI
        $empleado = Empleado::where('dni', $validatedData['dni'])->first();

        // Verificar si el empleado está activo
        if (!$empleado->is_active) {
            session()->flash('error', 'El empleado no está activo. No se puede registrar la asistencia.');
            return; // Detener la ejecución
        }

        // Obtener la asistencia para la fecha actual (si ya existe)
        $asistencia = Asistencia::firstOrCreate(
            ['fecha_asistencia' => $fecha], // Buscar por fecha
            ['fecha_asistencia' => $fecha]  // Crear si no existe
        );

        // Verificar si el empleado ya tiene un registro para el día
        $registroExistente = ControlAsistencia::where('empleado_id', $empleado->id)
            ->where('asistencia_id', $asistencia->id)
            ->first();

        if (!$registroExistente) {
            // Si no existe un registro, crear uno con la hora de entrada
            ControlAsistencia::create([
                'asistencia_id' => $asistencia->id,
                'empleado_id' => $empleado->id,
                'estado' => 'asistió',  // Asumiendo que el estado siempre es 'asistió' al registrar la entrada
                'hora_entrada' => now()->format('H:i:s'),
                'hora_salida' => null, // No se registra la salida aún
            ]);
            session()->flash('message', 'Entrada registrada correctamente');
        } else {
            // Si ya existe el registro, verificar si ya se registró la salida
            if ($registroExistente->hora_salida) {
                session()->flash('error', 'Ya se registró la salida para hoy.');
            } else {
                // Si no se ha registrado la salida, actualizar la hora de salida
                $registroExistente->update([
                    'hora_salida' => now()->format('H:i:s'),
                ]);
                session()->flash('message', 'Salida registrada correctamente');
            }
        }
    }

    public function render()
    {
        return view('livewire.asistencias.asistencias-registro')->layout('layouts.app');
    }
}