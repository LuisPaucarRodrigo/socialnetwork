<table>
    <thead>
        <tr>
            <td>N° de Factura</td>
            <td>Fecha de Facturación</td>
            <td>Fecha de Depósito</td>
            <td>Monto</td>
        </tr>
    </thead>
    <tbody>
        @php
         $total = 0;
        @endphp
        @foreach ($general_earnings as $item)
        @php
            $monto = $item->deposit_date ? $item->amount : 0;
            $total += $monto;
        @endphp
        <tr>
            <td>{{ $item->invoice_number }}</td>
            <td>{{ $item->invoice_date }}</td>
            <td>{{ $item->deposit_date }}</td>
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
