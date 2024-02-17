<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>PDF</title>
    <style>
        th, td {
            border: 1px solid #000;
            font-size: 12px;
            padding: 2px;
        }
        table {
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            margin-bottom: 0; /* Esto elimina el espacio entre tablas */
        }
    </style>
</head>
<body>

    <table class="mt-3">
        <tbody>
              <tr>
                <td colspan="1" style="height: 70px"><img src="image/projectimage/logo_ccip.jpeg" width="80px" alt=""></td>
                <td colspan="3" style="text-align: center">{{ $preproject->description }}</td>
                <td colspan="2" style="text-align: center">C.T - {{ $preproject->quote->date }}</td>
              </tr>
          <tr>
            <td style="width: 82.5px">Cliente</td>
            <td style="width: 150px" style="text-align: center; font-weight: bold; width: 150px">{{ $preproject->customer }}</td>
            <td style="width: 60px">Supervisor:</td>
            <td style="width: 150px" style="text-align: center; font-weight: bold; width: 150px">{{ $preproject->quote->supervisor }}</td>
            <td style="width: 82.5px">Rev.</td>
            <td style="width: 150px" style="text-align: right; width: 150px;">{{ $preproject->quote->rev }}</td>
          </tr>
          <tr>
            <td style="width: 82.5px">Elaborado por:</td>
            <td style="width: 150px"  style="text-align: center; font-weight: bold;">CCIP</td>
            <td style="width: 60px">Jefe</td>
            <td style="width: 150px" style="text-align: center; font-weight: bold;">{{ $preproject->quote->boss }}</td>
            <td style="width: 82.5px">Fecha</td>
            <td style="width: 150px" style="text-align: right">{{ $preproject->quote->date }}</td>
          </tr>
        </tbody>
      </table>

    <table class="table mt-3">
        <thead>
          <tr>
            <th scope="col" colspan="7" style="text-align: center; background: #2e75b5; font-weight: normal;">Valorización</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width: 60px; text-align: center; background: #2e75b5; font-weight: normal;">Partida</td>
            <td style="width: 280px; text-align: center; background: #2e75b5; font-weight: normal;">Descripción</td>
            <td style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Unidad</td>
            <td style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Días</td>
            <td style="width: 67px; text-align: center; background: #2e75b5; font-weight: normal;">Metrado</td>
            <td style="width: 73px; text-align: center; background: #2e75b5; font-weight: normal;">Valor Unitario</td>
            <td style="width: 80px; text-align: center; background: #2e75b5; font-weight: normal;">Valor total</td>
          </tr>
          @php
            $subtotal = 0; // Inicializa una variable para almacenar la suma
            @endphp 
          @foreach ($preproject->quote->items as $index => $item)
          <tr>

            <td style="text-align: right">1.0{{ $index+1 }}</td>
            <td>{{ $item->description }}</td>
            <td style="text-align: center">{{ $item->unit }}</td>
            <td style="text-align: center">{{ number_format($item->days, 2) }}</td>
            <td style="text-align: center">{{ number_format($item->quantity, 2) }}</td>
            <td style="text-align: right">S/. {{ number_format($item->unit_price, 2) }}</td>
            <td style="text-align: right">S/. {{ number_format(($item->quantity * $item->unit_price), 2) }}</td>
          </tr>
          @php
            $subtotal += $item->quantity * $item->unit_price; // Suma al total el producto quantity * unit_price
          @endphp
          @endforeach
          @php
            $igv = $subtotal * 18/100;
            $total = $subtotal + $igv;
          @endphp
        </tbody>
      </table>

      <table>
        <tbody>
          <tr>
            <td style="width: 100px; border: none"></td>
            <td style="width: 150px; border: none"></td>
            <td style="width: 100px; border: none"></td>
            <td style="width: 104.5px; border: none"></td>
            <td style="width: 145.5px; font-size: 10.5px; text-align: right; background: #bcd6ed">SUB TOTAL ACUMULADO</td>
            <td style="width: 79px; font-size: 10.5px; text-align: right">S/. {{ number_format($subtotal, 2) }}</td>
          </tr>
          <tr>
            <td style="width: 60px; border: none"></td>
            <td style="width: 60px; border: none"></td>
            <td style="width: 60px; border: none"></td>
            <td style="width: 60px; border: none"></td>
            <td style="font-size: 10.5px; text-align: right; background: #bcd6ed">IGV 18%</td>
            <td style="font-size: 10.5px; text-align: right">S/. {{ number_format($igv, 2) }}</td>
          </tr>
          <tr>
            <td style="width: 60px; border: none"></td>
            <td style="width: 60px; border: none"></td>
            <td style="width: 60px; border: none"></td>
            <td style="width: 60px; border: none"></td>
            <td style="font-size: 10.5px; text-align: right; background: #bcd6ed">TOTAL</td>
            <td style="font-size: 10.5px; text-align: right">S/. {{ number_format($total, 2) }}</td>
          </tr>
        </tbody>
      </table>

      <table class="mt-3">
        <tbody>
            <tr>
                <td colspan="2" style="text-align: center; background: #2e75b5; font-weight: normal;">Condiciones Comerciales</td>
              </tr>
          <tr>
            <td style="width: 140px; border-right: none">Tiempo de entrega</td>
            <td style="width: 556px; border-left: none">: {{ $preproject->quote->deliverable_time }}</td>
          </tr>
          <tr>
            <td style="width: 140px; border-right: none">Lugar de entrega</td>
            <td style="width: 556px; border-left: none">: MDD</td>
          </tr>
          <tr>
            <td style="width: 140px; border-right: none">Forma de Pago</td>
            <td style="width: 556px; border-left: none">: Según acuerdo Comercial</td>
          </tr>
          <tr>
            <td style="width: 140px; border-right: none">Validez de la oferta</td>
            <td style="width: 556px; border-left: none">: {{ $preproject->quote->validity_time }}</td>
          </tr>
          <tr>
            <td style="width: 140px; height: 50px; border-right: none">Observaciones</td>
            <td style="width: 556px; border-left: none">: {{ $preproject->quote->observations }}</td>
          </tr>
        </tbody>
      </table>
      <table class="mt-3">
        <tbody>
            <tr>
                <td colspan="2" style="text-align: center; background: #2e75b5; font-weight: normal;">Cuentas Bancarias</td>
              </tr>
              <tr>
                <td style="width: 200px">Banco de Crédito del Perú (Soles)</td>
                <td style="width: 496.5px">215-2622816-0-06</td>
              </tr>
              <tr>
                <td style="width: 200px">CCI</td>
                <td style="width: 496.5px">002-21500262281600620</td>
              </tr>
              <tr>
                <td style="width: 200px">Caja Municipal Arequipa (Soles)</td>
                <td style="width: 496.5px">001-44342902100001001</td>
              </tr>
              <tr>
                <td style="width: 200px">CCI</td>
                <td style="width: 496.5px">803-001-001443429001-72</td>
              </tr>
              <tr>
                <td style="width: 200px">Caja Municipal Arequipa (Dólares)</td>
                <td style="width: 496.5px">001-44342902110102001</td>
              </tr>
              <tr>
                <td style="width: 200px">CCI</td>
                <td style="width: 496.5px">803-001-001443429002-70</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: center; background: #2e75b5; font-weight: normal;">Cuenta de detracciones</td>
              </tr>
              <tr>
                <td style="width: 200px">Banco de la Nación (Soles)</td>
                <td style="width: 496.5px">00-101-426491</td>
              </tr>

        </tbody>
      </table>
</body>


</html>
