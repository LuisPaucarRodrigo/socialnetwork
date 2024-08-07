<table>
    <thead>
        <tr>
            <td>Código</td>
            <td>Descripción</td>
            <td>Precio Unitario</td>
            <td>Cantidad</td>
            <td>Monto</td>
        </tr>
    </thead>
    <tbody>
        @php
         $total = 0;
        @endphp
        @foreach ($earnings as $item)
        @php
            $monto = $item->unit_price * $item->quantity;
            $total += $monto; // Acumula el monto al total
        @endphp
        <tr>
            <td>{{ $item->code }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->unit_price }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->unit_price ? number_format($item->unit_price * $item->quantity, 2) : '-'}}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td>{{ $total }}</td>
        </tr>
    </tbody>
</table>
