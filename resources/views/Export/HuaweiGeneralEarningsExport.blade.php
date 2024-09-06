<table>
    <thead>
        <tr>
            <td>N° de Factura</td>
            <td>Fecha de Facturación</td>
            <td>Fecha de Depósito</td>
            <td>N° Operación BCP</td>
            <td>Fecha de Detracción</td>
            <td>N° Operación Detracción</td>
            <td>Monto BCP</td>
            <td>Monto Detracción</td>
            <td>Monto</td>
        </tr>
    </thead>
    <tbody>
        @php
         $total = 0;
         $total_bcp = 0;
         $total_detraction = 0;
        @endphp
        @foreach ($general_earnings as $item)
        @php
            $monto = $item->deposit_date ? $item->amount : 0;
            $total += $monto;
            $total_bcp += $item->main_amount;
            $total_detraction += $item->detraction_amount;
        @endphp
        <tr>
            <td>{{ $item->invoice_number }}</td>
            <td>{{ $item->invoice_date }}</td>
            <td>{{ $item->deposit_date }}</td>
            <td>{{ $item->main_op_number }}</td>
            <td>{{ $item->detraction_date }}</td>
            <td>{{ $item->detraction_op_number }}</td>
            <td>{{ number_format($item->main_amount, 2) }}</td>
            <td>{{ number_format($item->detraction_amount, 2) }}</td>
            <td>{{ number_format($item->amount, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td>{{ $total_bcp }}</td>
            <td>{{ $total_detraction }}</td>
            <td>{{ $total }}</td>
        </tr>
    </tbody>
</table>
