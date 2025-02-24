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
            width: 100%;
            height: 4cm;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            box-sizing: border-box;
            text-align: center;
            font-weight: bold;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .header-table td {
            vertical-align: middle;
            padding: 5px;
            border: none;
            text-align: center;
            font-weight: bold;
        }
        .header-table img {
            max-height: 2cm;
            width: auto;
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
            font-size: 10px;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #7f8c8d;
        }
        .asistencia-container {
            margin: 20px auto;
            text-align: center;
            width: 90%;
        }

        .asistencia-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .asistencia-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
        }

        .asistencia-header {
            background: #3498db;
            color: white;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .asistencia-table td {
            padding: 8px;
            font-size: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .label {
            background: #ecf0f1;
            font-weight: bold;
            text-align: right;
            width: 25%;
        }

        .value {
            text-align: left;
            width: 25%;
        }

    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 20%; text-align: left;">
                    <img src="{{ public_path('img/logo_oti.png') }}" alt="Logo 1">
                </td>
                <td style="width: 60%; text-align: center;">
                    <h1>MUNICIPALIDAD PROVINCIAL DE EL COLLAO – LLAVE</h1>
                    <h2>OFICINA DE TECNOLOGIA E INFORMATICA</h2>
                </td>
                <td style="width: 20%; text-align: right;">
                    <img src="{{ public_path('img/logo_muni_ilave.png') }}" alt="Logo 2">
                </td>
            </tr>
        </table>
    </div>

    <div class="asistencia-container">
        <p class="asistencia-title">EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO</p>
        <table class="asistencia-table">
            <tr>
                <td colspan="4" class="asistencia-header"><center>CONTROL DE ASISTENCIA</center></td>
            </tr>
            @if ($empleadoSeleccionado)
                <tr>
                    <td class="label">NOMBRES Y APELLIDOS:</td>
                    <td class="value">{{ $empleadoSeleccionado->nombres }}</td>
                    <td class="label">DNI:</td>
                    <td class="value">{{ $empleadoSeleccionado->dni }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="4" class="asistencia-header"><center>REPORTE GENERAL DE ASISTENCIAS</center></td>
                </tr>
            @endif
            <tr>
                <td class="label">PROGRAMA DE ESTUDIOS:</td>
                <td colspan="3" class="value">PRACTICAS PREPROFESIONALES</td>
            </tr>
            <tr>
                <td class="label">MES:</td>
                <td class="value">{{ count($meses) > 0 ? implode(', ', $meses) : 'Sin datos' }}</td>
                <td class="label">AÑO:</td>
                <td class="value">{{ count($años) > 0 ? implode(', ', $años) : 'Sin datos' }}</td>
            </tr>
        </table>
    </div>
    

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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($asistencia->asistencia)->fecha_asistencia ? 
                        \Carbon\Carbon::parse($asistencia->asistencia->fecha_asistencia)->format('d/m/Y') : 'Sin fecha' }}</td>
                    <td>{{ optional($asistencia->asistencia)->fecha_asistencia ? 
                        \Carbon\Carbon::parse($asistencia->asistencia->fecha_asistencia)->locale('es')->isoFormat('dddd') : '-' }}</td>
                    <td>{{ $asistencia->empleado->nombres ?? 'No Asignado' }}</td>
                    <td>{{ $asistencia->estado }}</td>
                    <td>{{ $asistencia->estado !== 'falta' ? $asistencia->hora_entrada : '-' }}</td>
                    <td>{{ $asistencia->estado !== 'falta' ? $asistencia->hora_salida : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br><br>
    <table class="header-table" style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                ..................................................<br>
                FIRMA DEL PRACTICANTE<br>
                DNI N° ................
            </td>
            <td style="width: 50%; text-align: center;">
                ..................................................<br>
                FIRMA DEL RESPONSABLE<br>
                DNI N° ................
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Oficina de Tecnología de la Informática - El Collao Ilave. Todos los derechos reservados.</p>
    </div>
</body>
</html>
