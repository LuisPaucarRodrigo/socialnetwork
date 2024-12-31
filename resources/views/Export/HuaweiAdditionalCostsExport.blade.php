<table>
    <thead>
        <tr>
            <td>Tipo de Gasto</td>
            <td>Cuadrilla</td>
            <td>Empleado</td>
            <td>Fecha del Gasto</td>
            <td>Tipo de CDP</td>
            <td>N° de Documento</td>
            <td>N° de Operación</td>
            <td>RUC</td>
            <td>Descripción</td>
            <td>Monto</td>
            <td>Estado de Reembolso</td>
            <td>Fecha de Depósito de E.C.</td>
            <td>N° de Operación de E.C.</td>
            <td>Monto de E.C.</td>
        </tr>
    </thead>
    <tbody>
        @php
            $total_amount = 0;
            $total_ec_amount = 0;
        @endphp
        @foreach ($additional_costs as $item)
        @php
            $total_amount += $item->amount;
            $total_ec_amount += $item->ec_amount;
        @endphp
        <tr>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->zone }}</td>
            <td>{{ $item->employee }}</td>
            <td>{{ $item->expense_date }}</td>
            <td>{{ $item->cdp_type }}</td>
            <td>{{ $item->doc_number }}</td>
            <td>{{ $item->op_number }}</td>
            <td>{{ $item->ruc }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ number_format($item->amount, 2) }}</td>
            <td>{{ $item->refund_status }}</td>
            <td>{{ $item->ec_expense_date }}</td>
            <td>{{ $item->ec_op_number }}</td>
            <td>{{ number_format($item->ec_amount, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $total_amount }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $total_ec_amount }}</td>
        </tr>
    </tbody>
</table>
