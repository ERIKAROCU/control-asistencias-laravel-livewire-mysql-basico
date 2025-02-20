<div class="container mx-auto p-4 bg-gray-50 rounded-lg shadow-md">
    <div>
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">Gestión de Empleados</h1>
    </div>
    <div>
        <ul>
            <li>
                <button wire:click="dispatch('showModalEmpleado')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-user"></i>+ Agregar
                </button>
            </li>
        </ul>
    </div>
    <br>
    <div class="mb-4 flex justify-between items-center">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar por nombre, correo, DNI o cargo..."
            class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >

        <select
            wire:model.live="isActive"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="">Todos</option>
            <option value="1">Activos</option>
            <option value="0">Inactivos</option>
        </select>

        <select
            wire:model.live="perPage"
            class="px-6 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="10">10 por página</option>
            <option value="25">25 por página</option>
            <option value="50">50 por página</option>
        </select>
    </div>

    <div wire:key="empleados-table">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Número</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Nombres</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">DNI</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Celular</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Fecha Nac</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Estado</th>
                    <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empleados as $empleado)
                    <tr wire:key="empleado-{{ $empleado->id }}">
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $empleado->id }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $empleado->nombres }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $empleado->dni }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $empleado->celular }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $empleado->fecha_nacimiento }}</td>
                        <td class="py-2 px-4 border-b text-sm text-gray-800 text-center">
                            @if ($empleado->is_active)
                                <span class="text-green-600">Activo</span>
                            @else
                                <span class="text-red-600">Inactivo</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            {{-- Botón Editar --}}
                            <button wire:click="dispatch('edit', { id: {{ $empleado->id }} })"
                                class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-1 rounded" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>

                            {{-- Botón Eliminar --}}
                            {{-- <button onclick="confirmDelete({{ $empleado->id }})"
                                class="bg-red-500 text-white px-3 py-1 rounded">
                                <i class="fas fa-trash"></i>
                            </button> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $empleados->links() }}
    </div>

    {{-- Incluir el modal --}}
    @livewire('empleados.empleados-form')

    {{-- Script para confirmar eliminación --}}
    <script>
        function confirmDelete(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
                @this.call('deleteRow', id);
            }
        }
    </script>   
</div>
