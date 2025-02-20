<div>
    @if($modalVisible)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 overflow-y-auto">
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-200">
                    <h2 class="text-lg font-bold mb-4">
                        Asistencias del {{ $fechaSeleccionada ? \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') : 'N/A' }}
                    </h2>

                    <!-- Tabla para mostrar las asistencias -->
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">#</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Nombres</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Entrada</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Salida</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Estado</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-900">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($empleadosConAsistencia as $index => $empleado)
                                <tr>
                                    <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-800">{{ $empleado['empleado']->nombres }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-800">
                                        @if(in_array($empleado['empleado']->id, $asistenciasSeleccionadas))
                                            {{ $empleado['tieneAsistencia'] ? $empleado['tieneAsistencia']->hora_entrada : 'Sin registro' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-800">
                                        @if(in_array($empleado['empleado']->id, $asistenciasSeleccionadas))
                                            {{ $empleado['tieneAsistencia'] ? $empleado['tieneAsistencia']->hora_salida : 'Sin registro' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-800">
                                        @if(in_array($empleado['empleado']->id, $asistenciasSeleccionadas))
                                            {{ $empleado['tieneAsistencia'] ? $empleado['tieneAsistencia']->estado : 'Sin registro' }}
                                        @else
                                            Faltó
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b text-sm text-gray-800">
                                        <input type="checkbox" wire:click="toggleAsistencia({{ $empleado['empleado']->id }})"
                                               {{ in_array($empleado['empleado']->id, $asistenciasSeleccionadas) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center">No hay empleados activos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Botones -->
                    <div class="col-span-2 flex justify-end mt-4 space-x-2">
                        <button wire:click="$set('modalVisible', false)" type="button"
                            class="px-4 py-2 bg-gray-300 rounded">
                            Cancelar
                        </button>
                        <button wire:click="guardarAsistencias" type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
