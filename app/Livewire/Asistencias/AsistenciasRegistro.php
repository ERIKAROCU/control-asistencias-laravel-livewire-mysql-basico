<?php

namespace App\Livewire\Asistencias;

use App\Models\Asistencia;
use App\Models\ControlAsistencia;
use App\Models\Empleado;
use App\Models\Feriado; 
use Livewire\Component;

class AsistenciasRegistro extends Component
{
    public $dni;

    protected $rules = [
        'dni' => 'required|exists:empleados,dni',
    ];

    protected $messages = [
        'dni.required' => 'El campo DNI es obligatorio.',
        'dni.exists' => 'El empleado no existe.',
    ];

    protected $listeners = ['swal' => 'swal'];

    public function submit()
{
    $validatedData = $this->validate();
    $fecha = now()->format('Y-m-d');

    // Verificar si es feriado
    if (Feriado::where('fecha', $fecha)->exists()) {
        $this->dispatch('swal', [
            'title' => 'Error',
            'text' => 'Hoy es feriado. No se pueden registrar asistencias.',
            'icon' => 'error'
        ]);
        $this->reset('dni');
        return;
    }

    // Buscar empleado
    $empleado = Empleado::where('dni', $validatedData['dni'])->first();
    if (!$empleado || !$empleado->is_active) {
        $this->dispatch('swal', [
            'title' => 'Error',
            'text' => 'El empleado no está activo o no existe.',
            'icon' => 'error'
        ]);
        $this->reset('dni');
        return;
    }

    // Obtener o crear asistencia
    $asistencia = Asistencia::firstOrCreate(['fecha_asistencia' => $fecha]);

    // Verificar si ya tiene un registro hoy
    $registroExistente = ControlAsistencia::where('empleado_id', $empleado->id)
        ->where('asistencia_id', $asistencia->id)
        ->first();

    if (!$registroExistente) {
        ControlAsistencia::create([
            'asistencia_id' => $asistencia->id,
            'empleado_id' => $empleado->id,
            'estado' => 'asistió',
            'hora_entrada' => now()->format('H:i:s'),
            'hora_salida' => null,
        ]);
        $message = 'Entrada registrada correctamente.';
        $icon = 'success';
    } else {
        if ($registroExistente->hora_salida) {
            $message = 'Ya se registró la salida para hoy.';
            $icon = 'error';
        } else {
            $registroExistente->update([
                'hora_salida' => now()->format('H:i:s'),
            ]);
            $message = 'Salida registrada correctamente.';
            $icon = 'success';
        }
    }

    $this->dispatch('swal', [
        'title' => 'Asistencia',
        'text' => $message,
        'icon' => $icon
    ]);

    $this->reset('dni');
    session()->forget('message');
    session()->forget('error');
}

    public function render()
    {
        return view('livewire.asistencias.asistencias-registro')->layout('layouts.app');
    }
}
