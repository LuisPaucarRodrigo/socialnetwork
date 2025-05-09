<table>
    <thead>
        <tr>
            <th>Zona</th>
            <th>Tipo de Gasto</th>
            <th>Tipo de Documento</th>
            <th>RUC</th>
            <th>Número de Documento</th>
            <th>Fecha de Documento</th>
            <th>Monto</th>
            <th>Monto sin IGV</th>
            <th>Descripción</th>
            <th>Número de Operacion</th>
            <th>Fecha de Operacion</th>
            <th>Estado de Estado de Cuenta</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $item)
        <tr>
            <td>{{ $item->zone }}</td>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->type_doc }}</td>
            <td>{{ $item->ruc }}</td>
            <td>{{ $item->doc_number }}</td>
            <td>{{ $item->doc_date }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->real_amount }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->operation_number }}</td>
            <td>{{ $item->operation_date }}</td>
            <td>{{ $item->real_state }}</td>
        </tr>
        @endforeach
    </tbody>
</table>