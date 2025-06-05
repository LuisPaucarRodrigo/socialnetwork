<table>
    <thead>
        <tr>
            <th>Fecha de operación</th>
            <th>Número de operación</th>
            <th>Descripción</th>
            <th>Cargo</th>
            <th>Aboono</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->operation_date }}</td>
            <td>{{ $item->operation_number }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->charge }}</td>
            <td>{{ $item->payment }}</td>
            <td>{{ $item->state }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
