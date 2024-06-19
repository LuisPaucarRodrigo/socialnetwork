<!DOCTYPE html>
<html>
<head>
    <title>Exportar Productos Huawei</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Anexo Nombre</th>
                <th>Unidad de Anexo</th>
                <th>Cantidad</th>
                <th>Zona</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->anexe_name }}</td>
                    <td>{{ $product->anexe_unit }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->zone }}</td>
                    <td>{{ (!$product->price_guide1 && !$product->price_guide2 ? '' : ($product->price_guide1 ? number_format($product->price_guide1->unit_price, 2) : number_format($product->price_guide2->unit_price, 2))) }}</td>
                    <td>{{ (!$product->price_guide1 && !$product->price_guide2 ? '' : ($product->price_guide1 ? number_format($product->price_guide1->unit_price*$product->quantity, 2) : number_format($product->price_guide2->unit_price*$product->quantity, 2))) }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>TOTAL:</td>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
