<table>
    <thead>
        <tr>
            <td>Código</td>
            <td>Descripción</td>
            <td>Precio Unitario</td>
            <td>Cantidad</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($earnings as $item)
        <tr>
            <td>{{ $item->code }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->unit_price }}</td>
            <td>{{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
