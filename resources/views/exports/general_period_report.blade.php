<table>
    <thead>
        <tr>
            <th>Código PP</th>
            <th>Cód. Evento</th>
            <th>Actividad Operativa</th>
            <th>Fecha</th>
            <th>Detalle del Reporte</th>
            <th>Asistentes</th>
            <th>Acumulado Previo</th>
            <th>Nuevo Acumulado</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Esta variable nos ayuda a saber cuándo dejar las celdas en blanco para agrupar visualmente
            $currentEventCode = null;
        @endphp

        @foreach($data as $row)
            <tr>
                @if($row['event_code'] !== $currentEventCode)
                    <td>{{ $row['pp_code'] }}</td>
                    <td>{{ $row['event_code'] }}</td>
                    <td>{{ $row['actividad'] }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                
                <td>{{ $row['fecha'] }}</td>
                <td>{{ $row['reporte'] }}</td>
                <td>{{ $row['asistentes'] }}</td>
                <td>{{ $row['acumulado_previo'] }}</td>
                <td>{{ $row['nuevo_acumulado'] }}</td>
            </tr>

            @php
                // Guardamos el código actual para compararlo en la siguiente vuelta del bucle
                $currentEventCode = $row['event_code'];
            @endphp
        @endforeach

        <tr>
            <td colspan="5" style="text-align: right;"><strong>TOTALES GLOBALES DEL PERIODO</strong></td>
            <td><strong>{{ $totalPeriodo }}</strong></td>
            <td><strong>{{ $totalAcumuladoPrevio }}</strong></td>
            <td><strong>{{ $totalNuevoAcumulado }}</strong></td>
        </tr>
    </tbody>
</table>

