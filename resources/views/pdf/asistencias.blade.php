<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Reporte de Asistencias</h1>
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
                    <td>{{ $asistencia->hora_entrada }}</td>
                    <td>{{ $asistencia->hora_salida }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>