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
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $dato)
        <tr>
            <td>{{ $dato->zone }}</td>
            <td>{{ $dato->expense_type }}</td>
            <td>{{ $dato->type_doc }}</td>
            <td>{{ $dato->ruc }}</td>
            <td>{{ $dato->doc_number }}</td>
            <td>{{ $dato->doc_date }}</td>
            <td>{{ $dato->amount }}</td>
            <td>{{ $dato->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
