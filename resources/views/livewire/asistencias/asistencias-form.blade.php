<div>
    @if($modalVisible)
        <!-- Fondo oscurecido que cubre toda la pantalla -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 overflow-y-auto">
            <!-- Contenedor del modal -->
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-lg font-bold mb-4">
                        Nueva lista de asistencia
                    </h2>

                    <form wire:submit.prevent="save" class="gap-4">
                        <!-- Fecha de asistencia -->
                        <div class="col-span-1">
                            <label for="fecha_asistencia" class="block text-sm font-medium text-gray-700">Fecha de Asistencia</label>
                            <input type="date" wire:model="fecha_asistencia" id="fecha_asistencia" placeholder="fecha_asistencia"
                                class="mt-1 block w-full rounded-md border-gray-300 p-2">
                            @error('fecha_asistencia') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botones (col-span-2 para ocupar ambas columnas) -->
                        <div class="col-span-2 flex justify-end mt-4 space-x-2">
                            <button wire:click="$set('modalVisible', false)" type="button"
                                class="px-4 py-2 bg-gray-300 rounded">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded">
                                Crear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deshabilitar el desplazamiento de la página cuando el modal está abierto -->
        <style>
            body {
                overflow: hidden;
            }
        </style>
    @endif
</div>

<script>
    window.addEventListener('swal', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            timer: event.detail.timer || 2000, // Si no se pasa un tiempo, se usa 2 segundos por defecto
            showConfirmButton: event.detail.showConfirmButton || false, // Muestra el botón de confirmación si se necesita
            didClose: () => {
                // Aquí puedes agregar una acción cuando se cierre la alerta
            }
        });
    });
</script>
