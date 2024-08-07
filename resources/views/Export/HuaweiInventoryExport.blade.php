<table>
    <thead>
        <tr>
            <td>N°</td>
            <td>FECHA</td>
            <td>CODSAP</td>
            <td>DESCRIPCIÓN</td>
            <td>SERIE</td>
            <td>CONTRATA</td>
            <td>SITE DONDE SE SOLICITÓ LOS EQUIPOS EN PAP</td>
            <td>ESTATUS GENERAL</td>
            <td>OBSERVACIÓN</td>
            <td>FECHA DE INSTALACIÓN</td>
            <td>DIU DEL SITIO ASIGNADO</td>
            <td>VALOR MONETARIO</td>
            <td>EQUIPOS TRASPASADOS</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($equipments as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->huawei_entry_detail->huawei_entry->entry_date }}</td>
            <td>{{ $item->huawei_equipment->claro_code }}</td>
            <td>{{ $item->huawei_equipment->name }}</td>
            <td>{{ $item->serie_number }}</td>
            <td>CCIP</td>
            <td>{{ $item->huawei_entry_detail->assigned_site }}</td>
            <td>{{ $item->huawei_entry_detail->instalation_state ? 'INSTALADO' :
                  ($item->huawei_entry_detail->state == 'En Proyecto' ? 'EN SITE' :
                   ($item->huawei_entry_detail->state == 'Disponible' ? 'EN ALMACÉN' : 'DEVUELTO')) }}</td>
            <td>
                @php
                    $observation = $item->huawei_entry_detail->observation;
                    $parts = explode('/', $observation);
                    // Tomar la primera parte y recortar espacios en blanco
                    $result = isset($parts[0]) ? trim($parts[0]) : '';
                @endphp
                {{ $result }}
            </td>
            <td>{{ $item->huawei_entry_detail->latest_huawei_project_resource ? $item->huawei_entry_detail->latest_huawei_project_resource->huawei_project_liquidation?->instalation_date : '' }}</td>
            <td>{{ $item->huawei_entry_detail->assigned_diu }}</td>
            <td>{{ $item->huawei_entry_detail->unit_price }}</td>
            <td>
                @php
                    $observation = $item->huawei_entry_detail->observation;
                    $parts = explode('/', $observation);
                    // Si hay al menos dos partes, tomar la segunda parte y recortar espacios en blanco
                    $result = isset($parts[1]) ? trim($parts[1]) : '';
                @endphp
                {{ $result }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
