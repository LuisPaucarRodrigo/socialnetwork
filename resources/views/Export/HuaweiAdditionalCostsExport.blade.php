<table>
    <thead>
        <tr>
            <td>Tipo de Gasto</td>
            <td>RUC/DNI</td>
            <td>Zona</td>
            <td>Tipo de Documento</td>
            <td>Número de Documento</td>
            <td>Fecha de Documento</td>
            <td>Descripción</td>
            <td>Monto</td>
        </tr>
    </thead>
    <tbody>
        @php
         $total = 0;
        @endphp
        @foreach ($additional_costs as $item)
        @php
            $monto = $item->amount;
            $total += $monto;
        @endphp
        <tr>
            <td>{{ $item->expense_type }}</td>
            <td>{{ $item->ruc }}</td>
            <td>{{ $item->zpne }}</td>
            <td>{{ $item->type_doc }}</td>
            <td>{{ $item->doc_number }}</td>
            <td>{{ $item->doc_date }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ number_format($item->amount, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td>{{ $total }}</td>
        </tr>
    </tbody>
</table>
