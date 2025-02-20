<div class="w-100 bg-gray-800 shadow-md">
    <div class="p-4">
        <h2 class="text-lg font-bold">Panel de Control</h2>
    </div>
    <nav>
        <ul>
            <li><a href="{{ route('dashboard') }}" class="block p-4 hover:bg-gray-200">Dashboard</a></li>
            <li><a href="{{ route('empleados.empleados-table') }}" class="block p-4 hover:bg-gray-200">Empleados</a></li>

            <li x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="block w-full text-left p-4 hover:bg-gray-200 flex justify-between items-center">
                    Asistencias
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Submenú -->
                <ul x-show="open" @click.away="open = false" class="absolute left-0 w-full bg-gray-700 shadow-md rounded-lg mt-1">
                    <li><a href="{{ route('asistencias.asistencias-table') }}" class="block p-4 hover:bg-gray-200">Listas de asistencias</a></li>
                    <li><a href="{{ route('asistencias.asistencias-all') }}" class="block p-4 hover:bg-gray-200">Ver todas las asistencias</a></li>
                </ul>
            </li>
        

            {{-- <li><a class="block p-4 hover:bg-gray-200">Asistencias</a></li>
            <li><a href="{{ route('asistencias.asistencias-table') }}" class="block p-4 hover:bg-gray-200">Crear lista de asistencias</a></li>
            <li><a href="{{ route('asistencias.asistencias-all') }}" class="block p-4 hover:bg-gray-200">Ver todas las asistencias</a></li> --}}
            
            {{-- <li><a href="{{ route('documents.index') }}" class="block p-4 hover:bg-gray-200">Documentos</a></li>
            
            <!-- Mostrar solo si el usuario es admin -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <li><a href="{{ route('users.index') }}" class="block p-4 hover:bg-gray-200">Usuarios</a></li>
            @endif

            <!-- Mostrar solo si el usuario es admin -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <li><a href="{{ route('oficinas.oficina-table') }}" class="block p-4 hover:bg-gray-200">Oficinas</a></li>
            @endif --}}
        </ul>
    </nav>
</div>
