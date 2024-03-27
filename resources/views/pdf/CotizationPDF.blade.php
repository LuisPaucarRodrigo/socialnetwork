<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>PDF</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            margin-bottom: 0; /* Esto elimina el espacio entre tablas */
        }
        .td-custom {
            border: 2px solid #000;
            font-size: 12px;
            padding: 2px;
        }

    </style>
</head>
<body>
    <div style="width: 100%; height: 1000px; border: 2px solid #000; margin-top: 0px; text-align: center;">
        <img src="image/projectimage/caratula-ccip.jpg" alt="" style="max-width: 600px; height: auto; margin-top: 75px">
        <p class="mt-4" style="font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif" >Propuesta Económica:</p>
        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Propuesta</p>
        <p style="font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Proyecto:</p>
        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{ $preproject->quote->name }}</p>
        <p style="font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Asesor Comercial:</p>
        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Gustavo Flores Llerrena</p>
        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; color: blue; text-decoration: underline;">gflores@ccip.com.pe</p>
        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">992275316</p>
        <br>
        <br>
        <br>
        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Arequipa {{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $preproject->quote->date)->format('d/m/Y') }}</p>
        <br>
        <br>
        <br>
        <br>
        <p style="font-size: 18px; font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">“Innovación y tecnología al alcance de la sociedad”</p>
    </div>
    
    <table >
        <tbody>
              <tr>
                <td class="td-custom" colspan="1" style="height: 70px"><img src="image/projectimage/logo_ccip.jpeg" width="80px" alt=""></td>
                <td class="td-custom" colspan="3" style="text-align: center">{{ $preproject->quote->name }}</td>
                <td class="td-custom" colspan="2" style="text-align: center">C.T - {{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $preproject->quote->date)->format('d/m/Y') }}</td>
              </tr>
          <tr>
            <td class="td-custom" style="width: 82.5px">Cliente</td>
            <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold; width: 150px">{{ $preproject->customer }}</td>
            <td class="td-custom" style="width: 60px">Supervisor:</td>
            <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold; width: 150px">{{ $preproject->quote->supervisor }}</td>
            <td class="td-custom" style="width: 82.5px">Rev.</td>
            <td class="td-custom" style="width: 150px" style="text-align: right; width: 150px;">{{ $preproject->quote->rev }}</td>
          </tr>
          <tr>
            <td class="td-custom" style="width: 82.5px">Elaborado por:</td>
            <td class="td-custom" style="width: 150px"  style="text-align: center; font-weight: bold;">CCIP</td>
            <td class="td-custom" style="width: 60px">Jefe</td>
            <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold;">{{ $preproject->quote->boss }}</td>
            <td class="td-custom" style="width: 82.5px">Fecha</td>
            <td class="td-custom" style="width: 150px" style="text-align: right">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $preproject->quote->date)->format('d/m/Y') }}</td>
          </tr>
        </tbody>
      </table>

    <table class="table mt-3">
        <thead>
          <tr>
            <th class="td-custom"  scope="col" colspan="6" style="text-align: center; background: #2e75b5; font-weight: normal;">Productos</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td-custom" style="width: 60px; text-align: center; background: #2e75b5; font-weight: normal;">Partida</td>
            <td class="td-custom" style="width: 341px; text-align: center; background: #2e75b5; font-weight: normal;">Descripción</td>
            <td class="td-custom" style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Unidad</td>
            <td class="td-custom" style="width: 67px; text-align: center; background: #2e75b5; font-weight: normal;">Cantidad</td>
            <td class="td-custom" style="width: 73px; text-align: center; background: #2e75b5; font-weight: normal;">Valor Unitario</td>
            <td class="td-custom" style="width: 80px; text-align: center; background: #2e75b5; font-weight: normal;">Valor total</td>
          </tr>
          @php
            $subtotalProd = 0; // Inicializa una variable para almacenar la suma
            @endphp 
          @foreach ($preproject->quote->products as $index => $item)
          <tr>

            <td class="td-custom" style="text-align: right">1.0{{ $index+1 }}</td>
            <td class="td-custom" >{{ $item->purchase_product->name }}</td>
            <td class="td-custom" style="text-align: center">{{ $item->purchase_product->unit }}</td>
            <td class="td-custom" style="text-align: center">{{ $item->quantity }}</td>
            <td class="td-custom" style="text-align: right">S/. {{ number_format($item->unitary_price*(1+ $item->profit_margin/100), 2) }}</td>
            <td class="td-custom" style="text-align: right">S/. {{ number_format($item->quantity * $item->unitary_price*(1+ $item->profit_margin/100), 2) }}</td>
          </tr>
          @php
            $subtotalProd += $item->quantity * $item->unitary_price*(1+ $item->profit_margin/100); 
          @endphp
          @endforeach
          @php
          @endphp
        </tbody>
      </table>

    <table class="table mt-3">
        <thead>
          <tr>
            <th class="td-custom"  scope="col" colspan="7" style="text-align: center; background: #2e75b5; font-weight: normal;">Servicios</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td-custom" style="width: 60px; text-align: center; background: #2e75b5; font-weight: normal;">Partida</td>
            <td class="td-custom" style="width: 280px; text-align: center; background: #2e75b5; font-weight: normal;">Descripción</td>
            <td class="td-custom" style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Unidad</td>
            <td class="td-custom" style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Días</td>
            <td class="td-custom" style="width: 67px; text-align: center; background: #2e75b5; font-weight: normal;">Metrado</td>
            <td class="td-custom" style="width: 73px; text-align: center; background: #2e75b5; font-weight: normal;">Valor Unitario</td>
            <td class="td-custom" style="width: 80px; text-align: center; background: #2e75b5; font-weight: normal;">Valor total</td>
          </tr>
          @php
            $subtotal = 0; // Inicializa una variable para almacenar la suma
            @endphp 
          @foreach ($preproject->quote->items as $index => $item)
          <tr>

            <td class="td-custom" style="text-align: right">1.0{{ $index+1 }}</td>
            <td class="td-custom" >{{ $item->description }}</td>
            <td class="td-custom" style="text-align: center">{{ $item->unit }}</td>
            <td class="td-custom" style="text-align: center">{{ number_format($item->days, 2) }}</td>
            <td class="td-custom" style="text-align: center">{{ number_format($item->quantity, 2) }}</td>
            <td class="td-custom" style="text-align: right">S/. {{ number_format($item->unit_price *(1+ $item->profit_margin/100), 2) }}</td>
            <td class="td-custom" style="text-align: right">
              S/. {{ number_format(($item->quantity * $item->unit_price * $item->days *(1+ $item->profit_margin/100)), 2) }}
            </td>
          </tr>
          @php
            $subtotal += $item->quantity * $item->unit_price  * $item->days *(1+ $item->profit_margin/100); 
          @endphp
          @endforeach
          @php
          @endphp
        </tbody>
      </table>

      <table style="width: 100%; border: none; all: initial;">
        <tr>
            <td style="vertical-align: center; text-align: right; width: 468px;">
                <img src="image/projectimage/firma.jpg" width="100px" alt="">
            </td>
            
            <td style="vertical-align: top; width: 200px">
                <table>
                    <tbody>
                        <tr>
                            <td class="td-custom"  style="width: 146px; font-size: 10.5px; text-align: right; background: #bcd6ed">SUB TOTAL ACUMULADO</td>
                            <td class="td-custom" style="width: 83px; font-size: 10.5px; text-align: right">
                              S/. {{ number_format($subtotal + $subtotalProd, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-custom" style="font-size: 10.5px; text-align: right; background: #bcd6ed">IGV 18%</td>
                            <td class="td-custom" style="font-size: 10.5px; text-align: right">
                              S/. {{ number_format(($subtotal + $subtotalProd) * .18, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-custom" style="font-size: 10.5px; text-align: right; background: #bcd6ed">TOTAL</td>
                            <td class="td-custom" style="font-size: 10.5px; text-align: right">S/. {{ number_format(($subtotal + $subtotalProd) * 1.18, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>           
        </tr>
    </table>
      <table class="mt-3">
        <tbody>
            <tr>
                <td class="td-custom" colspan="2" style="text-align: center; background: #2e75b5; font-weight: normal;">Condiciones Comerciales</td>
              </tr>
          <tr>
            <td class="td-custom" style="width: 140px; border-right: none">Tiempo de entrega</td>
            <td class="td-custom" style="width: 562.5px; border-left: none">: {{ $preproject->quote->deliverable_time }}</td>
          </tr>
          <tr>
            <td class="td-custom" style="width: 140px; border-right: none">Lugar de entrega</td>
            <td class="td-custom" style="width: 562.5px; border-left: none">: MDD</td>
          </tr>
          <tr>
            <td class="td-custom" style="width: 140px; border-right: none">Forma de Pago</td>
            <td class="td-custom" style="width: 562.5px; border-left: none">: Según acuerdo Comercial</td>
          </tr>
          <tr>
            <td class="td-custom" style="width: 140px; border-right: none">Validez de la oferta</td>
            <td class="td-custom" style="width: 562.5px; border-left: none">: {{ $preproject->quote->validity_time }}</td>
          </tr>
          <tr>
            <td class="td-custom" style="width: 140px; height: 50px; border-right: none">Observaciones</td>
            <td class="td-custom" style="width: 562.5px; border-left: none">: {{ $preproject->quote->observations }}</td>
          </tr>
        </tbody>
      </table>
      <table class="mt-3">
        <tbody>
            <tr>
                <td class="td-custom" colspan="2" style="text-align: center; background: #2e75b5; font-weight: normal;">Cuentas Bancarias</td>
              </tr>
              <tr>
                <td class="td-custom" style="width: 200px">Banco de Crédito del Perú (Soles)</td>
                <td class="td-custom" style="width: 500.5px">215-2622816-0-06</td>
              </tr>
              <tr>
                <td class="td-custom" style="width: 200px">CCI</td>
                <td class="td-custom" style="width: 500.5px">002-21500262281600620</td>
              </tr>
              <tr>
                <td class="td-custom" style="width: 200px">Caja Municipal Arequipa (Soles)</td>
                <td class="td-custom" style="width: 500.5px">001-44342902100001001</td>
              </tr>
              <tr>
                <td class="td-custom" style="width: 200px">CCI</td>
                <td class="td-custom" style="width: 500.5px">803-001-001443429001-72</td>
              </tr>
              <tr>
                <td class="td-custom" style="width: 200px">Caja Municipal Arequipa (Dólares)</td>
                <td class="td-custom" style="width: 500.5px">001-44342902110102001</td>
              </tr>
              <tr>
                <td class="td-custom" class="td-custom" style="width: 200px">CCI</td>
                <td class="td-custom" style="width: 500.5px">803-001-001443429002-70</td>
              </tr>
              <tr>
                <td class="td-custom" colspan="2" style="text-align: center; background: #2e75b5; font-weight: normal;">Cuenta de detracciones</td>
              </tr>
              <tr>
                <td class="td-custom" style="width: 200px">Banco de la Nación (Soles)</td>
                <td class="td-custom" style="width: 500.5px">00-101-426491</td>
              </tr>

        </tbody>
      </table>
</body>


</html>
