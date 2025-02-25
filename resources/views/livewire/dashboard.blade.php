<div class="container mx-auto p-6 bg-gray-50 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Dashboard de Asistencias</h2>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="flex items-center p-5 bg-blue-500 text-white rounded-lg shadow-lg">
            <div class="text-4xl mr-4">👤</div>
            <div>
                <h3 class="text-lg font-semibold">Total Empleados Activos</h3>
                <p class="text-3xl font-bold">{{ $totalEmpleados }}</p>
            </div>
        </div>

        <div class="flex items-center p-5 bg-green-500 text-white rounded-lg shadow-lg">
            <div class="text-4xl mr-4">✅</div>
            <div>
                <h3 class="text-lg font-semibold">Asistencias Hoy</h3>
                <p class="text-3xl font-bold">{{ $totalAsistenciasHoy }}</p>
            </div>
        </div>

        <div class="flex items-center p-5 bg-red-500 text-white rounded-lg shadow-lg">
            <div class="text-4xl mr-4">❌</div>
            <div>
                <h3 class="text-lg font-semibold">Faltas Hoy</h3>
                <p class="text-3xl font-bold">{{ $totalAusentesHoy }}</p>
            </div>
        </div>

        <div class="flex items-center p-5 bg-yellow-500 text-white rounded-lg shadow-lg">
            <div class="text-4xl mr-4">⏳</div>
            <div>
                <h3 class="text-lg font-semibold">Tardanzas Hoy</h3>
                <p class="text-3xl font-bold">{{ $totalTardanzasHoy }}</p>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-200">
            <h3 class="text-lg font-semibold text-center">Cumpleaños de Empleados</h3>
            </div>
            <canvas id="cumpleEmpleadoChart" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-200">
                <h3 class="text-lg font-semibold text-center">Programas de Estudios</h3>
            </div>
            <canvas id="programasEstudiosChart" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-200">
                <h3 class="text-lg font-semibold text-center">Asistencias del Mes</h3>
            </div>
            <canvas id="asistenciasMesChart" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Tabla de asistencias recientes -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden mt-8">
        <div class="p-4 bg-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 text-center">Asistencias Recientes</h3>
        </div>
        <table class="w-full table-auto text-gray-700">
            <thead class="bg-gray-100 text-gray-900">
                <tr>
                    <th class="p-3 text-left">Empleado</th>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Entrada</th>
                    <th class="p-3 text-left">Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asistenciasRecientes as $asistencia)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $asistencia->empleado->nombres }}</td>
                        <td class="p-3">{{ $asistencia->asistencia->fecha_asistencia }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-lg text-white text-sm
                                @if($asistencia->estado == 'asistió') bg-green-500 
                                @elseif($asistencia->estado == 'tardanza') bg-yellow-500 
                                @elseif($asistencia->estado == 'falta') bg-red-500 
                                @else bg-gray-500 @endif">
                                {{ ucfirst($asistencia->estado) }}
                            </span>
                        </td>
                        <td class="p-3">{{ $asistencia->hora_entrada }}</td>
                        <td class="p-3">{{ $asistencia->hora_salida }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    const colores = ['red', 'blue', 'green', 'orange', 'purple', 'cyan'];

// Gráfico de Cumpleaños
const ctxCumple = document.getElementById('cumpleEmpleadoChart').getContext('2d');
const fechaProximoCumple = @json($cumpleEmpleado['proximoCumple']);
const nombresCumple = @json($cumpleEmpleado['nombres']);

const cumpleEmpleadoChart = new Chart(ctxCumple, {
    type: 'bar',
    data: {
        labels: @json($cumpleEmpleado['labels']),
        datasets: [{
            label: 'Cumpleaños',
            data: @json($cumpleEmpleado['data']),
            backgroundColor: @json($cumpleEmpleado['labels']).map(fecha =>
                fecha === fechaProximoCumple ? 'red' : 'blue'
            ),
            borderColor: @json($cumpleEmpleado['labels']).map(fecha =>
                fecha === fechaProximoCumple ? 'red' : 'blue'
            ),
            borderWidth: 2,
            borderRadius: 5,
            barPercentage: @json($cumpleEmpleado['labels']).map(fecha =>
                fecha === fechaProximoCumple ? 1 : 0.7 // Resalta la barra más grande
            ),
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { 
                display: true, 
                text: 'Cumpleaños de Empleados', 
                font: { size: 16 } 
            },
            tooltip: {
                callbacks: {
                    label: function (tooltipItem) {
                        const fecha = tooltipItem.label;
                        const nombres = nombresCumple[fecha] || [];
                        return nombres.length ? `Empleados: ${nombres.join(', ')}` : 'Sin datos';
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true }
        }
    }
});


// Gráfico de Programas de Estudios
const ctxProgramas = document.getElementById('programasEstudiosChart').getContext('2d');
const programasEstudiosChart = new Chart(ctxProgramas, {
    type: 'doughnut',
    data: {
        labels: @json($programasEstudios['labels']),
        datasets: [{
            data: @json($programasEstudios['data']),
            backgroundColor: colores,
            borderColor: '#fff',
            borderWidth: 2,
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Programas de Estudios', font: { size: 16 } }
        },
        cutout: '50%',
    }
});

// Gráfico de Asistencias del Mes
const ctxAsistencias = document.getElementById('asistenciasMesChart').getContext('2d');
const asistenciasMesChart = new Chart(ctxAsistencias, {
    type: 'line',
    data: {
        labels: @json($asistenciasMes['labels']),
        datasets: [
            {
                label: 'Asistencias',
                data: @json($asistenciasMes['asistencias']),
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                tension: 0.3,
                fill: true,
            },
            {
                label: 'Faltas',
                data: @json($asistenciasMes['faltas']),
                borderColor: 'red',
                backgroundColor: 'rgba(255, 0, 0, 0.2)',
                tension: 0.3,
                fill: true,
            },
            {
                label: 'Tardanzas',
                data: @json($asistenciasMes['tardanzas']),
                borderColor: 'orange',
                backgroundColor: 'rgba(255, 165, 0, 0.2)',
                tension: 0.3,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Asistencias del Mes', font: { size: 16 } }
        },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true }
        }
    }
});

</script>