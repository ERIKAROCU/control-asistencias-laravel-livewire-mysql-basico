<div>
    @if($modalVisible)
        <!-- Fondo oscurecido que cubre toda la pantalla -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 overflow-y-auto">
            <!-- Contenedor del modal -->
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-150">
                    <h2 class="text-lg font-bold mb-4">
                        {{ $isEditing ? 'Editar Empleado' : 'Nuevo Empleado' }}
                    </h2>

                    <form wire:submit.prevent="save" class="grid grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div class="col-span-1">
                            <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres</label>
                            <input type="text" wire:model="nombres" id="nombres" placeholder="Nombre completo"
                                class="mt-1 block w-full rounded-md border-gray-300 p-2">
                            @error('nombres') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-span-1">
                            <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                            <input type="dni" wire:model="dni" id="dni" placeholder="DNI"
                                class="mt-1 block w-full rounded-md border-gray-300 p-2">
                            @error('dni') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Celular -->
                        <div class="col-span-1">
                            <label for="celular" class="block text-sm font-medium text-gray-700">Celular</label>
                            <input type="text" wire:model="celular" id="celular" placeholder="celular"
                                class="mt-1 block w-full rounded-md border-gray-300 p-2">
                            @error('celular') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha de nacimiento -->
                        <div class="col-span-1">
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" wire:model="fecha_nacimiento" id="fecha_nacimiento" placeholder="fecha_nacimiento"
                                class="mt-1 block w-full rounded-md border-gray-300 p-2">
                            @error('fecha_nacimiento') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Estado -->
                        <div class="col-span-1">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select wire:model="is_active" id="is_active"
                                class="mt-1 block w-full rounded-md border-gray-300 p-2">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('is_active') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botones (col-span-2 para ocupar ambas columnas) -->
                        <div class="col-span-2 flex justify-end mt-4 space-x-2">
                            <button wire:click="$set('modalVisible', false)" type="button"
                                class="px-4 py-2 bg-gray-300 rounded">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded">
                                {{ $isEditing ? 'Actualizar' : 'Guardar' }}
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
            icon: event.detail.icon,
            showConfirmButton: true,
            timer: 1000
        });
    });
</script>
