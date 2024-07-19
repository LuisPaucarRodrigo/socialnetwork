<table>
    <thead>
        <tr>
            <th>Código Proyecto</th>
            <th>Nombre del Proyecto</th>
            <th>Zona</th>
            <th>Tipo de Gasto</th>
            <th>Tipo de Documento</th>
            <th>RUC</th>
            <th>Proveedor</th>
            <th>Número de Documento</th>
            <th>Fecha de Documento</th>
            <th>Monto</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($costs as $item)
        <tr>
            <td>{{ $item->project?->code }}</td>
            <td>{{ $item->project?->description }}</td>
            <td>{{ $item->zone }}</td>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->type_doc }}</td>
            <td>{{ $item->ruc }}</td>
            <td>{{ $item->provider?->company_name }}</td>
            <td>{{ $item->doc_number }}</td>
            <td>{{ $item->doc_date }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
