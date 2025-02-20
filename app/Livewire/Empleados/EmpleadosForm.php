<?php

namespace App\Livewire\Empleados;

use Livewire\Component;
use App\Models\Empleado;
use Illuminate\Validation\Rule;

class EmpleadosForm extends Component
{
    public $empleado_id;
    public $nombres, $dni, $celular,$fecha_nacimiento, $is_active=true;
    public $isEditing = false; // Determina si es edición o creación
    public $modalVisible = false;

    protected function rules()
    {
        return [
            'nombres' => 'required|string|max:255',
            'dni' => [
                'required',
                'string',
                'size:8',
                Rule::unique('empleados')->ignore($this->empleado_id), 
            ],
            'celular' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'required|date',
            'is_active' => 'required',
        ];
    }

    protected $messages = [
        'nombres.required' => 'El campo nombres es obligatorio.',
        'nombres.string' => 'El campo nombres debe ser una cadena de texto.',
        'nombres.max' => 'El campo nombres no debe exceder los 255 caracteres.',
        'dni.required' => 'El campo DNI es obligatorio.',
        'dni.string' => 'El campo DNI debe ser una cadena de texto.',
        'dni.size' => 'El campo DNI debe tener exactamente 8 caracteres.',
        'dni.unique' => 'El DNI ya está registrado.',
        'celular.string' => 'El campo celular debe ser una cadena de texto.',
        'celular.max' => 'El campo celular no debe exceder los 15 caracteres.',
        'fecha_nacimiento.required' => 'El campo fecha de nacimiento es obligatorio.',
        'fecha_nacimiento.date' => 'El campo fecha de nacimiento debe ser una fecha válida.',
        'is_active.required' => 'El campo estado es obligatorio.',
        'is_active.in' => 'El campo estado debe ser uno de los siguientes valores: activo, inactivo.',
    ];

    protected $listeners = ['edit' => 'loadEmpleado', 'showModalEmpleado' => 'showModalEmpleado', 'refreshTable' => '$refresh', 'swal' => 'swal'];

    public function loadEmpleado($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            session()->flash('error', 'El empleado no existe.');
            return;
        }

        $this->empleado_id = $empleado->id;
        $this->nombres = $empleado->nombres;
        $this->dni = $empleado->dni;
        $this->celular = $empleado->celular;
        $this->fecha_nacimiento = $empleado->fecha_nacimiento;
        $this->is_active = $empleado->is_active;
        $this->modalVisible = true;
    }

    public function showModalEmpleado()
    {
        $this->reset(['empleado_id', 'nombres', 'dni', 'celular', 'fecha_nacimiento']);
        $this->resetValidation();
        $this->isEditing = false;
        $this->modalVisible = true;
    }

    public function deleteRow($id)
    {
        Empleado::find($id)->delete();
        $this->dispatch('refreshTable');
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'nombres' => $this->nombres,
            'dni' => $this->dni,
            'celular' => $this->celular,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'is_active' => $this->is_active,
        ];

        Empleado::updateOrCreate(['id' => $this->empleado_id], $data);

        $message = $this->empleado_id ? 'Empleado actualizado.' : 'Empleado creado.';
        
        session()->flash('message', $message);

        $this->dispatch('swal', title: $message, icon: 'success');
        
        $this->modalVisible = false;
        $this->isEditing = true;
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.empleados.empleados-form');
    }
}
