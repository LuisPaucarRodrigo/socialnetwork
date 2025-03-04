<table>
    <thead>
        <tr>
            <td>Tipo de Gasto</td>
            <td>Zona</td>
            <td>DU del Proyecto</td>
            <td>Empleado</td>
            <td>Comprobante de Pago</td>
            <td>Fecha del Gasto</td>
            <td>N° de Serie</td>
            <td>Correlativo</td>
            <td>RUC</td>
            <td>Descripción</td>
            <td>Monto</td>
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
        @foreach ($expenses as $item)
        @php
            $total_amount += $item->amount;
            $total_ec_amount += $item->ec_amount;
        @endphp
        <tr>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->zone ?? $item->huawei_project?->huawei_site->name }}</td>
            <td>{{ $item->huawei_project?->assigned_diu }}</td>
            <td>{{ $item->employee }}</td>
            <td>{{ $item->cdp_type }}</td>
            <td>{{ $item->expense_date }}</td>
            <td>{{ $item->doc_number }}</td>
            <td>{{ $item->op_number }}</td>
            <td>{{ $item->ruc }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ number_format($item->amount, 2) }}</td>
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
            <td></td>
            <td>{{ $total_amount }}</td>
            <td></td>
            <td></td>
            <td>{{ $total_ec_amount }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
