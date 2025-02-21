<?php

namespace App\Livewire\Asistencias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\HoraDefecto;
use Illuminate\Support\Facades\Auth;

class AsistenciasTable extends Component
{
    use WithPagination;

    public $search = ''; // Búsqueda general
    public $perPage = 10; // Número de usuarios por página
    public $searchDate = ''; // Filtro por fecha
    public $hora_entrada; // Hora de entrada
    public $hora_salida; // Hora de salida

    protected $listeners = ['refreshTable' => '$refresh', 'deleteRow' => 'deleteRow', 'swal' => 'swal'];

    public function mount()
    {
        // Trae las horas por defecto de la base de datos cuando se carga el componente
        $horaDefecto = HoraDefecto::first(); // Puedes ajustar esto si hay más de un registro
        if ($horaDefecto) {
            $this->hora_entrada = $horaDefecto->hora_entrada_defecto;
            $this->hora_salida = $horaDefecto->hora_salida_defecto;
        }
    }
    

    public function actualizarHoraDefecto()
    {
        // Actualiza las horas en la base de datos
        $horaDefecto = HoraDefecto::first(); // O `find($id)` si tienes un ID específico
        if ($horaDefecto) {
            $horaDefecto->hora_entrada_defecto = $this->hora_entrada;
            $horaDefecto->hora_salida_defecto = $this->hora_salida;
            $horaDefecto->save();
        }

        // Opcional: Puedes agregar un mensaje de éxito si es necesario
        $message = 'Horas actualizadas correctamente!';
        session()->flash('message', $message);
        $this->dispatch('swal', title: $message, icon: 'success');
    }

    public function render()
    {
        // Obtener las horas por defecto
        $horadefecto = HoraDefecto::first(); // O puedes ajustar la lógica si necesitas el registro adecuado

        // Obtener las asistencias
        $asistencias = Asistencia::query()
            ->when($this->search, function ($query) {
                $query->where('fecha_asistencia', 'like', '%' . $this->search . '%');
            })
            ->orderBy('fecha_asistencia', 'desc')
            ->paginate($this->perPage);

        return view('livewire.asistencias.asistencias-table', compact('asistencias', 'horadefecto'))->layout('layouts.app');
    }
}
