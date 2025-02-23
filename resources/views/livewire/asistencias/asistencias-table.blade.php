<div class="container mx-auto p-4 bg-gray-50 rounded-lg shadow-md">
    <div>
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Gestión de Asistencias</h1>
    </div>
    <div class="flex justify-between items-center space-x-4 w-full">
        <!-- Columna de botón para nueva lista de asistencias -->
        <div>
            <button wire:click="dispatch('showModalAsistencia')" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-user"></i> + Nueva lista de asistencias
            </button>
        </div>
    
        <div class="flex flex-col items-center space-y-2">
            <h4 class="text-sm font-semibold mb-2">Hora por defecto</h4>
            <div class="flex space-x-2">
                <div>
                    <input type="time" wire:model="hora_entrada" value="{{ $hora_entrada }}" class="w-30 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="time" wire:model="hora_salida" value="{{ $hora_salida }}" class="w-30 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <button wire:click="actualizarHoraDefecto" class="px-4 py-1.5 bg-blue-500 text-white rounded-md hover:bg-blue-700 text-sm">
                        <i class="fas fa-clock"></i> Actualizar
                    </button>
                </div>
            </div>
            @if (session()->has('message'))
                <div class="mt-2 text-sm text-green-600">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </div>
    
    
    <br>
    <div class="mb-4 flex justify-between items-center">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar por fecha "
            class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >

        <div>
            <input
                type="date"
                wire:model.live="searchDate"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Filtrar por fecha"
            >
        </div>

        <select
            wire:model.live="perPage"
            class="px-6 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="10">10 por página</option>
            <option value="25">25 por página</option>
            <option value="50">50 por página</option>
        </select>
    </div>

    <div wire:key="asistencias-table">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Número</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Fecha</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Día</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asistencias as $asistencia)
                    <tr wire:key="asistencia-{{ $asistencia->id }}">
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $asistencia->id }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $asistencia->fecha_asistencia }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($asistencia->fecha_asistencia)->locale('es')->isoFormat('dddd') }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <button wire:click="dispatch('registrar_asistencia', { fecha_asistencia: '{{ $asistencia->fecha_asistencia }}' })"
                                class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-1 rounded" title="Registrar asistencia">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $asistencias->links() }}
    </div>

    @livewire('asistencias.asistencias-registro-manual')
    @livewire('asistencias.asistencias-form')
</div>
<script>
    window.addEventListener('swal', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.icon,
            showConfirmButton: true,
            timer: 1000
        });
    });
</script>