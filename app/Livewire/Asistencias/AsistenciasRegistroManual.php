<?php

namespace App\Livewire\Asistencias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ControlAsistencia;
use App\Models\Asistencia;
use App\Models\Empleado;
use App\Models\HoraDefecto;

class AsistenciasRegistroManual extends Component
{
    use WithPagination;

    public $asistencia_fecha;
    public $horas = [];
    public $fechas = [];
    public $empleados = [];
    public $modalVisible = false;
    public $fechaSeleccionada = null;
    public $perPage = 10;
    public $asistenciasSeleccionadas = [];

    protected $listeners = [
        'registrar_asistencia' => 'showModalAsistencias',
        'refreshTable' => '$refresh',
        'swal' => 'swal'
    ];

    public function showModalAsistencias($fecha_asistencia)
{
    $this->fechaSeleccionada = is_array($fecha_asistencia) && isset($fecha_asistencia['fecha_asistencia'])
        ? $fecha_asistencia['fecha_asistencia']
        : $fecha_asistencia;

    $this->modalVisible = true;

    // Inicializar la lista de asistencias seleccionadas con los empleados que tienen estado "asistió"
    $this->asistenciasSeleccionadas = ControlAsistencia::whereHas('asistencia', function ($q) {
        $q->where('fecha_asistencia', $this->fechaSeleccionada);
    })
    ->where('estado', 'asistió') // Solo empleados con estado "asistió"
    ->pluck('empleado_id')
    ->toArray();
}

public function toggleAsistencia($empleadoId)
{
    if (in_array($empleadoId, $this->asistenciasSeleccionadas)) {
        // Si el empleado ya estaba seleccionado, lo deseleccionamos
        $this->asistenciasSeleccionadas = array_diff($this->asistenciasSeleccionadas, [$empleadoId]);

        // Actualizamos el estado a "faltó" y eliminamos las horas de entrada y salida
        $asistencia = Asistencia::where('fecha_asistencia', $this->fechaSeleccionada)->first();
        if ($asistencia) {
            ControlAsistencia::where('asistencia_id', $asistencia->id)
                ->where('empleado_id', $empleadoId)
                ->update([
                    'estado' => 'falta',
                    'hora_entrada' => null, // Eliminar horas de entrada
                    'hora_salida' => null, // Eliminar horas de salida
                ]);
        }
    } else {
        // Si el empleado no estaba seleccionado, lo agregamos
        $this->asistenciasSeleccionadas[] = $empleadoId;

        // Obtenemos las horas por defecto
        $horaDefecto = HoraDefecto::first();

        // Actualizamos el estado a "asistió" y establecemos las horas de entrada y salida por defecto
        $asistencia = Asistencia::firstOrCreate(
            ['fecha_asistencia' => $this->fechaSeleccionada],
            ['created_at' => now(), 'updated_at' => now()]
        );

        ControlAsistencia::updateOrCreate(
            [
                'asistencia_id' => $asistencia->id,
                'empleado_id' => $empleadoId,
            ],
            [
                'estado' => 'asistió',
                'hora_entrada' => $horaDefecto->hora_entrada_defecto,
                'hora_salida' => $horaDefecto->hora_salida_defecto,
            ]
        );
    }

    // Forzar la actualización de la vista
    $this->dispatch('refresh'); // Notificar a Livewire que actualice la vista
}

    public function guardarAsistencias()
    {
        $message = 'Asistencias actualizadas';
        
        session()->flash('message', $message);

        $this->dispatch('swal', title: $message, icon: 'success');

        $this->modalVisible = false;
    }

    public function render()
    {
        $this->horas = HoraDefecto::all(); // campos hora_entrada_defecto y hora_salida_defecto
        $this->fechas = Asistencia::all();
        $this->empleados = Empleado::where('is_active', true)->get();

        $asistencias = ControlAsistencia::query()
            ->with('empleado')
            ->when($this->fechaSeleccionada, function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->where('fecha_asistencia', $this->fechaSeleccionada);
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        $empleadosConAsistencia = $this->empleados->map(function ($empleado) use ($asistencias) {
            $asistencia = $asistencias->firstWhere('empleado_id', $empleado->id);

            return [
                'empleado' => $empleado,
                'tieneAsistencia' => $asistencia,
            ];
        });

        return view('livewire.asistencias.asistencias-registro-manual', compact('empleadosConAsistencia'))
            ->layout('layouts.app');
    }
}
