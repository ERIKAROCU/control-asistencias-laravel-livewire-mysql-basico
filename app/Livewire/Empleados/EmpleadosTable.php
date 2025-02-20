<?php

namespace App\Livewire\Empleados;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;

class EmpleadosTable extends Component
{
    use WithPagination;

    public $search = ''; // Búsqueda general
    public $perPage = 10; // Número de usuarios por página
    public $isActive = ''; // Filtro de estado (activos/inactivos)

    protected $listeners = ['refreshTable' => '$refresh', 'deleteRow' => 'deleteRow'];

    public function render()
    {
        $empleados = Empleado::query()
            ->when($this->search, function ($query) {
                $query->where('nombres', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%')
                    ->orWhere('celular', 'like', '%' . $this->search . '%');
            })
            ->when($this->isActive !== '', function ($query) {
                $query->where('is_active', $this->isActive);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.empleados.empleados-table', compact('empleados'))->layout('layouts.app');
    }

}
