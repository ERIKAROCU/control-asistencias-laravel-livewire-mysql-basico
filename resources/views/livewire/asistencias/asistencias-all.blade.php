<div class="container mx-auto p-4 bg-gray-50 rounded-lg shadow-md">
    <div>
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Listas de Asistencias</h1>
    </div>

    <!-- Filtros en dos filas -->
    <div class="mb-4 space-y-4">
        <!-- Primera fila de filtros -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Búsqueda general -->
            <div>
                <label for="search" class="text-gray-500">Busqueda avanzada</label>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar por nombre o estado"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Filtro por empleado -->
            <div>
                <label for="userName" class="text-gray-500">Busqueda por empleado</label>
                <select
                    wire:model.live="userName"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Empleados (Todo)</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->nombres }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por estado -->
            <div>
                <label for="searchState" class="text-gray-500">Busqueda por estado</label>
                <select
                    wire:model.live="searchState"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Estado (Todo)</option>
                    <option value="asistio">Asistencias</option>
                    <option value="falta">Faltas</option>
                </select>
            </div>

            <!-- Filtro por fecha específica -->
            <div>
                <label for="fechaFiltro" class="text-gray-500">Busqueda por fecha</label>
                <input
                    type="date"
                    wire:model.live="fechaFiltro"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Segunda fila de filtros -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filtro por rango de fechas -->
            <div>
                <label class="text-gray-500">Busqueda por rangos de fechas</label>
                <div class="col-span-2 flex space-x-2">
                    <input
                        type="date"
                        wire:model.live="rangoFechas.inicio"
                        class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Inicio"
                    >
                    <input
                        type="date"
                        wire:model.live="rangoFechas.fin"
                        class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Fin"
                    >
                </div>
            </div>

            <!-- Filtro por día de la semana -->
            <div>
                <label for="diaFiltro" class="text-gray-500">Busqueda por dia</label>
                <select
                    wire:model.live="diaFiltro"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Día (Todo)</option>
                    <option value="2">Lunes</option>
                    <option value="3">Martes</option>
                    <option value="4">Miércoles</option>
                    <option value="5">Jueves</option>
                    <option value="6">Viernes</option>
                    <option value="7">Sábado</option>
                    <option value="8">Domingo</option>
                </select>
            </div>

            <!-- Filtro por número de registros por página -->
            <div>
                <label for="perPage" class="text-gray-500">Paginamiento</label>
                <select
                    wire:model.live="perPage"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

            <!-- Botón de Resetear Filtros -->
            <div>
                <button
                    wire:click="resetFilters"
                    class="w-full px-4 py-1 bg-blue-400 text-black rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                <i class="fas fa-sync-alt"></i>
                 Resetear Filtros
                </button>
                <button
                    wire:click="downloadPdf"
                    class="w-full px-4 py-1 bg-green-400 text-black rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <i class="fas fa-file-pdf"></i>
                 Descargar PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de asistencias -->
    <div wire:key="asistencias-table">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Número</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Fecha</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Día</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Nombres</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Estado</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Entrada</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asistencias as $asistencia)
                    <tr wire:key="asistencia-{{ $asistencia->id }}">
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $asistencia->id }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            {{ $asistencia->asistencia ? \Carbon\Carbon::parse($asistencia->asistencia->fecha_asistencia)->format('d/m/Y') : 'Sin fecha' }}
                        </td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($asistencia->asistencia->fecha_asistencia)->locale('es')->isoFormat('dddd') }}
                        </td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            {{ $asistencia->empleado->nombres ?? 'No Asignado' }}
                        </td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            @if($asistencia->estado !== 'falta')
                                <p class="py-2 px-4 border-b text-sm text-green-600">{{ $asistencia->estado }}</p>
                            @else
                                <p class="py-2 px-4 border-b text-sm text-red-600">falto</p>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            @if($asistencia->estado !== 'falta')
                                {{ $asistencia->hora_entrada }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">
                            @if($asistencia->estado !== 'falta')
                                {{ $asistencia->hora_salida }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $asistencias->links() }}
    </div>
</div>