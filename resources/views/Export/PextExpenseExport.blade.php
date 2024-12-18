<table>
    <thead>
        <tr>
            <!-- <th>Proyecto</th>
            <th>Centro de Costo</th> -->
            <th>Zona</th>
            <th>Tipo de Gasto</th>
            <th>Tipo de Documento</th>
            <th>RUC</th>
            <th>Proveedor</th>
            <th>Número de Operacion</th>
            <th>Fecha de Operacion</th>
            <th>Número de Documento</th>
            <th>Fecha de Documento</th>
            <th>Monto</th>
            <th>Monto sin IGV</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $item)
        <tr>
            <!-- <td>{{ $item->cicsa_assignation?->project_name }}</td>
            <td>{{ $item->cicsa_assignation?->cost_center }}</td> -->
            <td>{{ $item->zone }}</td>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->type_doc }}</td>
            <td>{{ $item->ruc }}</td>
            <td>{{ $item->provider?->company_name }}</td>
            <td>{{ $item->operation_number }}</td>
            <td>{{ $item->operation_date }}</td>
            <td>{{ $item->doc_number }}</td>
            <td>{{ $item->doc_date }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->real_amount }}</td>
            <td>{{ $item->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>