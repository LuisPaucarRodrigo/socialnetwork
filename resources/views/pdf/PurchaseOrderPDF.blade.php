<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>{{ $purchase_order->purchase_quote->purchasing_requests->title }}</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            margin-bottom: 0; /* Esto elimina el espacio entre tablas */
        }
        .td-custom {
            font-size: 14px;
            padding-left: 10px;
        }

        .td-custom2 {
            border: 1.5px solid #000;
            font-size: 14px;
            padding-left: 10px;
        }
    </style>
</head>
<body>

    <table style="width: 100%;">
        <tr>
            <td style="width: 80px; vertical-align: middle;"><img src="image/projectimage/logo_ccip.jpeg" width="150px" alt="" style=""></td>
            <td style="padding-left: 50px">
                <p style="margin-top: 30px; margin-left: 120; line-height: 0.5; font-weight: bold; font-size: 18px">Orden de Compra</p>
                <p style="margin-left: 120; line-height: 0.5;">Fecha: {{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $purchase_order->purchase_quote->quote_deadline)->format('d/m/Y') }}</p>
                <p style="margin-left: 120; line-height: 0.5;">Número de Orden: {{ $purchase_order->code }}</p>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 30px">
        <tr>
            <td style="width: 300px;  padding-right: 50px">
                <div style="padding-left: 5px; line-height: 1;">
                    <p style="font-size: 14px; font-weight: bold; line-height: 0.5;">Facturar a:</p>
                    <p style="font-size: 14px; line-height: 0.5;">CONPROCO S.R.L.</p>
                    <p style="font-size: 14px; line-height: 0.5;">RUC: 20559246272</p>
                    <p style="font-size: 14px;">CAL.PERAL NRO. 119 INT. 102 --- (FRENTE CONTRALORIA) AREQUIPA - AREQUIPA - AREQUIPA</p>
                    <p style="margin-top: 10px; font-size: 14px; font-weight: bold;">Enviar a:</p>
                    <p style="font-size: 14px; line-height: 0.5;">CONPROCO S.R.L.</p>
                    <p style="font-size: 14px;">CAL.PERAL NRO. 119 INT. 102 --- (FRENTE CONTRALORIA) AREQUIPA - AREQUIPA - AREQUIPA</p>
                    <p style="font-size: 14px; line-height: 0.5;">AREQUIPA</p>
                    <p style="font-size: 14px; line-height: 0.5;">Perú</p>
                    <p style="margin-top: 10px; font-size: 14px; font-weight: bold">Tipo de Pago: </p>
                    <p style="font-size: 14px;">{{ $purchase_order->purchase_quote->payment_type }}</p> 
                </div> 
            </td>
            <td style="display: flex; justify-content: flex-start; align-content: flex-start; width: 325px; margin-left: 10px">
                <div style="border: 1px solid #000;">
                    <p style="padding-top: 15px; font-size: 14px; margin-right: 60px; padding-left: 5px; line-height: 0.5; font-weight: bold;">A: </p>
                    <p style="margin-right: 60px; font-size: 14px; padding-left: 5px; line-height: 0.5; font-weight: bold;">{{ $purchase_order->purchase_quote->provider->company_name }}</p>
                    <p style="margin-right: 60px; font-size: 14px; padding-left: 5px; line-height: 0.5;">RUC: <span style="font-weight: bold;">{{ $purchase_order->purchase_quote->provider->ruc }}</span></p>
                    <p style="margin-right: 60px; font-size: 14px; padding-left: 5px; line-height: 0.5;">Dirección: <span style="font-weight: bold;">{{ $purchase_order->purchase_quote->provider->address }}</span></p>
                    <p style="margin-right: 60px; font-size: 14px; padding-left: 5px; line-height: 0.5;">Cel: {{ $purchase_order->purchase_quote->provider->phone1 }}</p>
                    <p style="margin-right: 60px; font-size: 14px; padding-left: 5px; line-height: 0.5;">Contacto: {{ $purchase_order->purchase_quote->provider->contact_name }}</p>
                    <p style="margin-right: 60px; font-size: 14px; padding-left: 5px; line-height: 0.5;">Email: {{ $purchase_order->purchase_quote->provider->email }}</p>

                </div>
            </td>
        </tr>
    </table>



    <table style="margin-top: 30px">
        <tr>
            <td style="border: 1px solid #000; width: 250px;  padding-right: 50px">
                <div style="padding-left: 5px; padding-top: 15px">
                    <p style="font-size: 14px; font-weight: bold; line-height: 0.5;">Persona de contacto:</p>
                    <p style="font-size: 14px; line-height: 0.5;">Nombre: {{ $user->name }}</p>
                    <p style="font-size: 14px; line-height: 0.5;">Email: {{ $user->email }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table style="margin-top: 10px">
        <tr>
            <td style="width: 450px;  padding-right: 50px">
                <div style="padding-top: 15px">
                    <p style="font-size: 14px; line-height: 1;">Enviar factura junto a documento xml.</p>
                </div>
            </td>
        </tr>
    </table>
    
    <table class="mt-3" style="">
        <thead>
          <tr>
            <th class="td-custom2"  scope="col" colspan="6" style="text-align: center; font-weight: normal;">Productos</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td-custom2" style="width: 55px; text-align: center; font-weight: normal;">Partida</td>
            <td class="td-custom2" style="width: 270px; text-align: center; font-weight: normal;">Código y Descripción</td>
            <td class="td-custom2" style="width: 55px; text-align: center; font-weight: normal;">Cantidad</td>
            <td class="td-custom2" style="width: 63px; text-align: center; font-weight: normal;">Moneda</td>
            <td class="td-custom2" style="width: 80px; text-align: center; font-weight: normal;">Valor unitario</td>
            <td class="td-custom2" style="width: 90px; text-align: center; font-weight: normal;">Valor total</td>
          </tr>
          @php
            $total = 0;
          @endphp 
          @foreach ($purchase_order->purchase_quote->purchase_quote_products as $index => $item)
          <tr>

            <td class="td-custom2" style="text-align: right">1.0{{ $index+1 }}</td>
            <td class="td-custom2" >{{ $item->purchase_product->code . ' - ' . $item->purchase_product->name }}</td>
            <td class="td-custom2" style="text-align: center">{{ $item->quantity }}</td>
            <td class="td-custom2" style="text-align: center">{{ $item->purchase_quote->currency }}</td>
            <td class="td-custom2" style="text-align: right">
                @if ($item->purchase_quote->currency == 'dolar')
                    $ {{ !$item->purchase_quote->igv ? number_format($item->unitary_amount/1.18 , 2) : number_format($item->unitary_amount, 2) }}
                @elseif ($item->purchase_quote->currency == 'sol')
                    S/. {{ !$item->purchase_quote->igv ? number_format($item->unitary_amount/1.18 , 2) : number_format($item->unitary_amount, 2) }}
                @endif
            </td>
            <td class="td-custom2" style="text-align: right">
                @if ($item->purchase_quote->currency == 'dolar')
                    ${{ !$item->purchase_quote->igv ? number_format($item->quantity * $item->unitary_amount/1.18, 2) : number_format($item->quantity * $item->unitary_amount, 2) }}
                @elseif ($item->purchase_quote->currency == 'sol')
                    S/. {{ !$item->purchase_quote->igv ? number_format($item->quantity * $item->unitary_amount/1.18, 2) : number_format($item->quantity * $item->unitary_amount, 2) }}
                @endif
            </td>
          </tr>
          @endforeach
          @if (!$purchase_order->purchase_quote->igv)
          <tr>
            <th class="td-custom2"  scope="col" colspan="6" style="text-align: right; font-weight: normal; font-weight: bold">Este valor no incluye IGV: {{ $purchase_order->purchase_quote->currency == 'sol' ? 'S/. ' : '$ '}} {{ number_format($purchase_order->purchase_quote->purchase_quote_products->sum(function($item){
                return !$item->purchase_quote->igv ? number_format($item->quantity * $item->unitary_amount/1.18, 2) : number_format($item->quantity * $item->unitary_amount, 2);
            }), 2) }} </th>
          </tr>
          @else
          <tr>
            <th class="td-custom2"  scope="col" colspan="6" style="text-align: right; font-weight: normal; font-weight: bold">Este valor incluye IGV: {{ $purchase_order->purchase_quote->currency == 'sol' ? 'S/. ' : '$ '}} {{ number_format($purchase_order->purchase_quote->purchase_quote_products->sum(function($item){
                return !$item->purchase_quote->igv ? $item->quantity * $item->unitary_amount/1.18 : $item->quantity * $item->unitary_amount;
            }), 2) }} </th>
          </tr>
          @endif
        </tbody>
      </table>
      <table class="mt-3" style="">
        <thead>
            <tr>
                <th class="td-custom2" scope="col" colspan="5" style="text-align: center; font-weight: normal;">Pagos</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 200px; text-align: center; font-weight: normal; border: 1.5px solid #000; font-size: 14px;">Descripción</td>
                <td style="width: 100px; text-align: center; font-weight: normal; border: 1.5px solid #000; font-size: 14px;">Estado</td>
                <td style="width: 170.5px; text-align: center; font-weight: normal; border: 1.5px solid #000; font-size: 14px;">Número de Operación</td>
                <td style="width: 100px; text-align: center; font-weight: normal; border: 1.5px solid #000; font-size: 14px;">Fecha del Pago</td>
                <td style="width: 100px; text-align: center; font-weight: normal; border: 1.5px solid #000; font-size: 14px;">Monto</td>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach ($purchase_order->purchase_quote->payment as $index => $item)
            <tr>
                <td style="text-align: center; border: 1.5px solid #000; font-size: 14px;">{{ $item->description }}</td>
                <td style="text-align: center; border: 1.5px solid #000; font-size: 14px;">{{ $item->state ? 'Pendiente' : 'Completado' }}</td>
                <td style="text-align: center; border: 1.5px solid #000; font-size: 14px;">{{ $item->operation_number ? $item->operation_number : '-' }}</td>
                <td style="text-align: center; border: 1.5px solid #000; font-size: 14px;">{{ $item->date ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $item->date)->format('d/m/Y') : '-' }}</td>
                <td style="text-align: center; border: 1.5px solid #000; font-size: 14px; text-align: right;">{{ $item->purchase_quote->currency == 'sol' ? 'S/. ' : '$ '}}{{ $item->amount }}</td>
            </tr>
            @php
                $total += $item->amount;
            @endphp
            @endforeach
            <tr>
                <td colspan="5" class="td-custom2" style="text-align: right; font-weight: normal; font-weight: bold">
                Total: {{$purchase_order->purchase_quote->currency == 'sol' ? 'S/. ' : '$ ' }} {{ $total }}</td>
            </tr>
        </tbody>
    </table>
    

</body>
</html>
