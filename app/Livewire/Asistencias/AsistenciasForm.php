<?php

namespace App\Livewire\Asistencias;

use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Validation\Rule;

class AsistenciasForm extends Component
{
    public $asistencia_id;
    public $fecha_asistencia;
    public $modalVisible = false;

    protected function rules()
    {
        return [
            'fecha_asistencia' => 'required|date',
        ];
    }

    protected $messages = [
        'fecha_asistencia.required' => 'El campo fecha de asistencia es obligatorio.',
        'fecha_asistencia.date' => 'El campo fecha de asistencia debe ser una fecha válida.',
    ];

    protected $listeners = ['showModalAsistencia' => 'showModalAsistencia', 'refreshTable' => '$refresh', 'swal' => 'swal'];

    public function showModalAsistencia($id = null)
    {
        $this->resetValidation();
        $this->reset();

        if ($id) {
            $this->asistencia_id = $id;
            $this->loadAsistencia($id);
        }

        $this->modalVisible = true;
    }

    public function save()
    {
        // Validar los datos
        $this->validate($this->rules());

        // Verificar si ya existe una asistencia con la misma fecha
        $existingAsistencia = Asistencia::where('fecha_asistencia', $this->fecha_asistencia)->first();

        if ($existingAsistencia && $existingAsistencia->id !== $this->asistencia_id) {
            session()->flash('error', 'Ya existe un registro de asistencia para esta fecha.');
            $this->dispatch('swal', title: 'Error', text: 'Ya existe un registro de asistencia para esta fecha.', icon: 'error');
            $this->modalVisible = false;
            return;
        }

        // Si no existe un registro con la misma fecha, crear o actualizar la asistencia
        $data = [
            'fecha_asistencia' => $this->fecha_asistencia,
        ];

        Asistencia::updateOrCreate(['id' => $this->asistencia_id], $data);

        $message = 'Lista de asistencia creada.';
        
        session()->flash('message', $message);

        $this->dispatch('swal', title: $message, icon: 'success');
        
        $this->modalVisible = false;
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.asistencias.asistencias-form');
    }
}
