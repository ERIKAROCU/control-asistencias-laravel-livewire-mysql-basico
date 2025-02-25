<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\ControlAsistencia;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalEmpleados;
    public $totalAsistenciasHoy;
    public $totalAusentesHoy;
    public $totalTardanzasHoy;
    public $asistenciasRecientes;
    public $cumpleEmpleado;
    public $programasEstudios;
    public $asistenciasMes;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $hoy = Carbon::today();

        $this->totalEmpleados = Empleado::where('is_active', 1)->count();
        $this->totalAsistenciasHoy = ControlAsistencia::whereHas('asistencia', function ($query) use ($hoy) {
            $query->where('fecha_asistencia', $hoy);
        })->where('estado', 'asistio')->count();

        $this->totalAusentesHoy = ControlAsistencia::whereHas('asistencia', function ($query) use ($hoy) {
            $query->where('fecha_asistencia', $hoy);
        })->where('estado', 'falta')->count();

        $this->totalTardanzasHoy = ControlAsistencia::whereHas('asistencia', function ($query) use ($hoy) {
            $query->where('fecha_asistencia', $hoy);
        })->where('estado', 'tardanza')->count();

        $this->asistenciasRecientes = ControlAsistencia::with(['empleado', 'asistencia'])
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $this->cumpleEmpleado = $this->getCumpleEmpleado();
        $this->programasEstudios = $this->getProgramasEstudios();
        $this->asistenciasMes = $this->getAsistenciasMes();
    }

    public function getCumpleEmpleado()
    {
        $hoy = Carbon::today();
        $empleados = Empleado::where('is_active', 1)->get();

        // Contar cuántos empleados cumplen años en cada fecha
        $cumpleaños = $empleados->groupBy(function ($empleado) {
            return Carbon::parse($empleado->fecha_nacimiento)->format('d M');
        })->map(function ($group) {
            return [
                'cantidad' => $group->count(),
                'nombres' => $group->pluck('nombres')->toArray(),
            ];
        });

        // Encontrar el próximo cumpleaños
        $proximoCumple = $empleados->sortBy(function ($empleado) use ($hoy) {
            $fechaCumple = Carbon::parse($empleado->fecha_nacimiento)->year($hoy->year);
            if ($fechaCumple->lt($hoy)) {
                $fechaCumple->addYear();
            }
            return $fechaCumple;
        })->first();

        $fechaProximo = Carbon::parse($proximoCumple->fecha_nacimiento)->format('d M');

        return [
            'labels' => $cumpleaños->keys(),
            'data' => $cumpleaños->pluck('cantidad'),
            'nombres' => $cumpleaños->map->nombres,
            'proximoCumple' => $fechaProximo,
        ];
    }

    public function getProgramasEstudios()
    {
        $programas = Empleado::where('is_active', 1)
            ->select('programa_estudios')
            ->get()
            ->groupBy('programa_estudios')
            ->map->count();

        return [
            'labels' => $programas->keys(),
            'data' => $programas->values(),
        ];
    }

    public function getAsistenciasMes()
    {
        $hoy = Carbon::today();
        $inicioMes = $hoy->copy()->startOfMonth();
        $finMes = $hoy->copy()->endOfMonth();

        $asistencias = ControlAsistencia::whereHas('asistencia', function ($query) use ($inicioMes, $finMes) {
            $query->whereBetween('fecha_asistencia', [$inicioMes, $finMes]);
        })->get();

        $asistenciasPorDia = $asistencias->groupBy(function ($asistencia) {
            return Carbon::parse($asistencia->asistencia->fecha_asistencia)->format('Y-m-d');
        });

        $diasMes = collect();
        for ($i = 1; $i <= $finMes->day; $i++) {
            $diasMes->push($inicioMes->copy()->addDays($i - 1)->format('Y-m-d'));
        }

        $dataAsistencias = $diasMes->map(function ($dia) use ($asistenciasPorDia) {
            return $asistenciasPorDia->get($dia, collect())->where('estado', 'asistió')->count();
        });

        $dataFaltas = $diasMes->map(function ($dia) use ($asistenciasPorDia) {
            return $asistenciasPorDia->get($dia, collect())->where('estado', 'falta')->count();
        });

        $dataTardanzas = $diasMes->map(function ($dia) use ($asistenciasPorDia) {
            return $asistenciasPorDia->get($dia, collect())->where('estado', 'tardanza')->count();
        });

        return [
            'labels' => $diasMes->map(function ($dia) {
                return Carbon::parse($dia)->format('d M');
            }),
            'asistencias' => $dataAsistencias,
            'faltas' => $dataFaltas,
            'tardanzas' => $dataTardanzas,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}