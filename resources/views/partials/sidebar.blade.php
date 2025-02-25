<div class="w-50 h-screen bg-gray-900 shadow-lg text-white flex flex-col">
    <!-- Encabezado del Sidebar -->
    <div class="p-6 flex items-center space-x-3 border-b border-blue-800">
        {{-- <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-20 h-20"> --}}
        <h2 class="text-xl font-bold">Panel de Control</h2>
    </div>

    <!-- Menú de Navegación -->
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- Empleados -->
            <li>
                <a href="{{ route('empleados.empleados-table') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-user"></i> <span>Empleados</span>
                </a>
            </li>

            <!-- Menú Desplegable: Asistencias -->
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-blue-800 transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-users"></i> <span>Asistencias</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Submenú -->
                <ul x-show="open" x-collapse class="pl-6 space-y-1 border-l border-blue-700 mt-1">
                    <li>
                        <a href="{{ route('asistencias.asistencias-table') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-800 transition">
                            <i class="fas fa-file-signature"></i> <span>Listas de asistencias</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('asistencias.asistencias-all') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-blue-800 transition">
                            <i class="fas fa-file"></i> <span>Ver todas las asistencias</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
