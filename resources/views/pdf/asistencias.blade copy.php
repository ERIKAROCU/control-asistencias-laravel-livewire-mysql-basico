<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            padding: 10px;
        }
        .header img {
            width: 70px; /* Tamaño más pequeño para la imagen */
            margin-right: 20px; /* Espacio entre la imagen y el título */
        }
        .header .title {
            text-align: center;
            color: #2c3e50;
            font-size: 20px;
        }
        .header .title h1 {
            margin: 0;
            font-size: 22px;
        }
        .header .title h2 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header .title p {
            font-size: 16px;
            color: #7f8c8d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <table class="border:none">
            <tr>
                <td>
                    <img src="{{ public_path('img/logo_oti.png') }}" alt="Logo 1">
                </td>
                <td>
                    <h1>MUNICIPALIDAD PROVINCIAL DE EL COLLAO – LLAVE</h1>
                    <h2>OFICINA DE TECNOLOGIA E INFORMATICA</h2>
                </td>
                <td>
                    <img src="{{ public_path('img/logo_muni_ilave.png') }}" alt="Logo 2">
                </td>
            </tr>
        </table>
        <div class="title">
            <p>EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO</p>
            {{-- <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p> --}}
        </div>
    </div>

    <!-- Tabla de Asistencias -->
    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Fecha</th>
                <th>Día</th>
                <th>Nombres</th>
                <th>Estado</th>
                <th>Entrada</th>
                <th>Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $asistencia)
                <tr>
                    <td>{{ $asistencia->id }}</td>
                    <td>{{ $asistencia->asistencia ? \Carbon\Carbon::parse($asistencia->asistencia->fecha_asistencia)->format('d/m/Y') : 'Sin fecha' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asistencia->asistencia->fecha_asistencia)->locale('es')->isoFormat('dddd') }}</td>
                    <td>{{ $asistencia->empleado->nombres ?? 'No Asignado' }}</td>
                    <td>{{ $asistencia->estado }}</td>
                    <td>
                        @if($asistencia->estado !== 'falta')
                            {{ $asistencia->hora_entrada }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
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

    <!-- Pie de página -->
    <div class="footer">
        <p>© {{ date('Y') }} Nombre de la Empresa. Todos los derechos reservados.</p>
    </div>
</body>
</html>
