<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        @csrf
        <div>
            <label for="dni" class="block text-sm font-medium">Número DNI:</label>
            <input
                type="password"
                id="dni"
                wire:model="dni"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                required
                placeholder="Introduce tu DNI"
                autocomplete="off"
            />
            @error('dni') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-[#2152b5] text-white px-4 py-2 rounded">Registrar Asistencia</button>
    </form>
</div>