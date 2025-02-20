<?php

namespace App\Livewire\Asistencias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\ControlAsistencia;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;


class AsistenciasAll extends Component
{
    use WithPagination;

    public $search = ''; // Búsqueda general
    public $perPage = 10; // Número de registros por página
    public $userName = ''; // Filtro por nombres de usuario
    public $searchState = ''; // Filtro por estado (asistió, falta)
    public $fechaFiltro = ''; // Filtro por fecha específica
    public $rangoFechas = ['inicio' => '', 'fin' => '']; // Filtro por rango de fechas
    public $diaFiltro = ''; // Filtro por día de la semana
    public $empleados = [];
    public $fechas = [];

    protected $listeners = ['refreshTable' => '$refresh', 'deleteRow' => 'deleteRow'];

    public function resetFilters()
    {
        $this->reset([
            'search',       // Restablece la búsqueda general
            'userName',     // Restablece el filtro por empleado
            'searchState',  // Restablece el filtro por estado
            'fechaFiltro',  // Restablece el filtro por fecha específica
            'rangoFechas',  // Restablece el filtro por rango de fechas
            'diaFiltro',    // Restablece el filtro por día de la semana
            'perPage',      // Restablece el número de registros por página
        ]);
    }

    public function downloadPdf()
    {
        // Obtener los datos filtrados
        $asistencias = ControlAsistencia::with(['empleado', 'asistencia'])
            ->when($this->search, function ($query) {
                $query->whereHas('empleado', function ($q) {
                    $q->where('nombres', 'like', '%' . $this->search . '%');
                })
                ->orWhere('estado', 'like', '%' . $this->search . '%')
                ->orWhereHas('asistencia', function ($q) {
                    $q->where('fecha_asistencia', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->userName !== '', function ($query) {
                $query->where('empleado_id', $this->userName);
            })
            ->when($this->searchState !== '', function ($query) {
                $query->where('estado', $this->searchState);
            })
            ->when($this->fechaFiltro, function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->whereDate('fecha_asistencia', $this->fechaFiltro);
                });
            })
            ->when($this->rangoFechas['inicio'] && $this->rangoFechas['fin'], function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->whereBetween('fecha_asistencia', [
                        $this->rangoFechas['inicio'],
                        $this->rangoFechas['fin']
                    ]);
                });
            })
            ->when($this->diaFiltro, function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->whereRaw('DAYOFWEEK(fecha_asistencia) = ?', [$this->diaFiltro]);
                });
            })
            ->orderBy(Asistencia::select('fecha_asistencia')->whereColumn('control_asistencias.asistencia_id', 'asistencias.id'), 'desc')
            ->get();

        // Cargar la vista del PDF con los datos
        $pdf = Pdf::loadView('pdf.asistencias', compact('asistencias'));

        // Descargar el PDF
        return Response::streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'asistencias.pdf');
    }

    public function render()
    {
        $this->fechas = Asistencia::all();
        $this->empleados = Empleado::all();

        $asistencias = ControlAsistencia::with(['empleado', 'asistencia'])
            ->when($this->search, function ($query) {
                $query->whereHas('empleado', function ($q) {
                    $q->where('nombres', 'like', '%' . $this->search . '%');
                })
                ->orWhere('estado', 'like', '%' . $this->search . '%')
                ->orWhereHas('asistencia', function ($q) {
                    $q->where('fecha_asistencia', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->userName !== '', function ($query) {
                $query->where('empleado_id', $this->userName);
            })
            ->when($this->searchState !== '', function ($query) {
                $query->where('estado', $this->searchState);
            })
            ->when($this->fechaFiltro, function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->whereDate('fecha_asistencia', $this->fechaFiltro);
                });
            })
            ->when($this->rangoFechas['inicio'] && $this->rangoFechas['fin'], function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->whereBetween('fecha_asistencia', [
                        $this->rangoFechas['inicio'],
                        $this->rangoFechas['fin']
                    ]);
                });
            })
            ->when($this->diaFiltro, function ($query) {
                $query->whereHas('asistencia', function ($q) {
                    $q->whereRaw('DAYOFWEEK(fecha_asistencia) = ?', [$this->diaFiltro]);
                });
            })
            ->orderBy(Asistencia::select('fecha_asistencia')->whereColumn('control_asistencias.asistencia_id', 'asistencias.id'), 'desc')
            ->paginate($this->perPage);

        return view('livewire.asistencias.asistencias-all', [
            'asistencias' => $asistencias
        ])->layout('layouts.app');
    }
}