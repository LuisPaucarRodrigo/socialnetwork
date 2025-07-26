<table>
    <thead>
        <tr>
            <th>Fecha de operación</th>
            <th>Cod de Operación</th>
            <th>Denominación</th>
            <th>Factura</th>
            <th>Boleta de Venta</th>
            <th>RR.HH</th>
            <th>Efectivo</th>
            <th>Debe</th>
            <th>Haber</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item['operation_date'] }}</td>
            <td>{{ $item['operation_number'] }}</td>
            <td>{{ $item['description'] }}</td>
            <td>
                @foreach($item['bills'] as $bill)
                {{ $bill }}<br>
                @endforeach
            </td>
            <td>
                @foreach($item['sales_receipt'] as $e)
                {{ $e }}<br>
                @endforeach
            </td>
            <td>
                @foreach($item['rr_hh'] as $e)
                {{ $e }}<br>
                @endforeach
            </td>
            <td>{{ $item['cash'] }}</td>
            <td>{{ $item['expenses'] }}</td>
            <td>{{ $item['income'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>