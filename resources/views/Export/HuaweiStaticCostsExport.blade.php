<table>
    <thead>
        <tr>
            <td>Tipo de Gasto</td>
            <td>Cuadrilla</td>
            <td>Fecha del Gasto</td>
            <td>Monto</td>
        </tr>
    </thead>
    <tbody>
        @php
         $total = 0;
        @endphp
        @foreach ($static_costs as $item)
        @php
            $monto = $item->amount;
            $total += $monto;
        @endphp
        <tr>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->zone }}</td>
            <td>{{ $item->cost_date }}</td>
            <td>{{ number_format($item->amount, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td>{{ $total }}</td>
        </tr>
    </tbody>
</table>
