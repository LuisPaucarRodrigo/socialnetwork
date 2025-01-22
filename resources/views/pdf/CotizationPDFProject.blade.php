<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <title>{{ $project->cicsa_assignation->project_name }}</title>
  <style>
    table {
      border-collapse: collapse;
      margin: 0;
      padding: 0;
      margin-bottom: 0;
      /* Esto elimina el espacio entre tablas */
    }

    .td-custom {
      border: 2px solid #000;
      font-size: 12px;
      padding: 2px;
    }
  </style>
</head>

<body>
  @php
  function numberTwoDecimal($number, $decimal = 2){
  $stringNumber = number_format($number, $decimal);
  $formattedNumber = str_replace(',', '', $stringNumber);
  return floatval($formattedNumber);
  };
  @endphp
  <div style="width: 100%; height: 1000px; border: 2px solid #000; margin-top: 0px; text-align: center;">
    <img src="image/projectimage/caratula-ccip.jpg" alt="" style="max-width: 600px; height: auto; margin-top: 75px">
    <p class="mt-4" style="font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Propuesta Económica</p>
    <br>
    <p style="font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Proyecto:</p>
    <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{ $project->cicsa_assignation->project_name }}</p>
    <p style="font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Asesor Comercial:</p>
    <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{ $project->project_quote->user->name }}</p>
    <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; color: blue; text-decoration: underline;">{{ $project->project_quote->user->email }}</p>
    <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{ $project->project_quote->user->phone }}</p>
    <br>
    <br>
    <br>
    <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Arequipa {{ \Illuminate\Support\Carbon::now()->format('d/m/Y') }}</p>
    <br>
    <br>
    <br>
    <br>
    <p style="font-size: 18px; font-weight: bold; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">“Innovación y tecnología al alcance de la sociedad”</p>
  </div>
  @php
  $jefe = $project->cost_line_id === 1 ? 'Luis Herrera':'prueba2';
  $supervisor = $project->cost_line_id === 1 ? 'Carlos Caceres':'suerpprueba2';
  @endphp
  <table>
    <tbody>
      <tr>
        <td class="td-custom" colspan="1" style="height: 70px;"><img src="image/projectimage/logo_ccip.jpeg" width="80px" alt=""></td>
        <td class="td-custom" colspan="3" style="text-align: center; font-weight: bold; font-size: 18px">{{ strtoupper($project->cicsa_assignation->project_name) }}</td>
        <td class="td-custom" colspan="2" style="text-align: center; font-weight: bold; font-size: 18px">{{ strtoupper($project->serialized_code) }}</td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 82.5px">Cliente</td>
        <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold; width: 150px">{{ $project->cicsa_assignation->customer }}</td>
        <td class="td-custom" style="width: 60px">Supervisor:</td>
        <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold; width: 150px">{{ $supervisor }}</td>
        <td class="td-custom" style="width: 82.5px">Rev.</td>
        <td class="td-custom" style="width: 150px" style="text-align: right; width: 150px;">
          1
        </td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 82.5px">Elaborado por:</td>
        <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold;">CCIP</td>
        <td class="td-custom" style="width: 60px">Jefe</td>
        <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold;">{{ $jefe }}</td>
        <td class="td-custom" style="width: 82.5px">Fecha</td>
        <td class="td-custom" style="width: 150px" style="text-align: right">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $project->cicsa_assignation->assignation_date)->format('d/m/Y') }}</td>
      </tr>
    </tbody>
  </table>

  <table class="table mt-3">
    <thead>
      <tr>
        <th class="td-custom" scope="col" colspan="7" style="text-align: center; background: #2e75b5; font-weight: normal;">Productos</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td-custom" style="width: 60px; text-align: center; background: #2e75b5; font-weight: normal;">Partida</td>
        <td class="td-custom" style="width: 265px; text-align: center; background: #2e75b5; font-weight: normal;">Descripción</td>
        <td class="td-custom" style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Unidad</td>
        <td class="td-custom" style="width: 67px; text-align: center; background: #2e75b5; font-weight: normal;">Dias</td>
        <td class="td-custom" style="width: 67px; text-align: center; background: #2e75b5; font-weight: normal;">Metrado</td>
        <td class="td-custom" style="width: 73px; text-align: center; background: #2e75b5; font-weight: normal;">Valor Unitario</td>
        <td class="td-custom" style="width: 80px; text-align: center; background: #2e75b5; font-weight: normal;">Valor total</td>
      </tr>
      @php
      $subtotalProd = 0; // Inicializa una variable para almacenar la suma

      @endphp
      @foreach ($project->project_quote->project_quote_valuations as $index => $item)
      <tr>

        <td class="td-custom" style="text-align: right">1.0{{ $index+1 }}</td>
        <td class="td-custom">{{ $item['description'] }}</td>
        <td class="td-custom" style="text-align: center">{{ $item['unit'] }}</td>
        <td class="td-custom" style="text-align: center">{{ $item['days'] }}</td>
        <td class="td-custom" style="text-align: center">{{ $item['metrado'] }}</td>
        <td class="td-custom" style="text-align: right">
          S/. {{ number_format($item['unit_value'], 2) }}
        </td>
        <td class="td-custom" style="text-align: right">S/. {{ number_format($item['days'] * $item['metrado'] *numberTwoDecimal($item['unit_value'],4), 2) }}</td>
      </tr>
      @php
      $subtotalProd += floatval($item['days'] * $item['metrado'] * numberTwoDecimal($item['unit_value'], 4));
      $fee = $project->project_quote->fee ? $subtotalProd * 0.10 : 0; // Calcula el fee solo si es true
      $igv = $subtotalProd * 0.18; // Siempre calcula el IGV
      $total = $subtotalProd + $fee + $igv; // Suma el fee solo si corresponde
      @endphp
      @endforeach
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
              <td class="td-custom" style="width: 146px; font-size: 10.5px; text-align: right; background: #bcd6ed">SUB TOTAL ACUMULADO</td>
              <td class="td-custom" style="width: 83px; font-size: 10.5px; text-align: right">
                S/. {{ number_format($subtotalProd, 2) }}
              </td>
            </tr>
            @if($project->project_quote->fee)
            <tr>
              <td class="td-custom" style="font-size: 10.5px; text-align: right; background: #bcd6ed">FEE 10%</td>
              <td class="td-custom" style="font-size: 10.5px; text-align: right">
                S/. {{ number_format($fee, 2) }}
              </td>
            </tr>
            @endif

            <tr>
              <td class="td-custom" style="font-size: 10.5px; text-align: right; background: #bcd6ed">IGV 18%</td>
              <td class="td-custom" style="font-size: 10.5px; text-align: right">
                S/. {{ number_format($igv, 2) }}
              </td>
            </tr>
            <tr>
              <td class="td-custom" style="font-size: 10.5px; text-align: right; background: #bcd6ed">TOTAL</td>
              <td class="td-custom" style="font-size: 10.5px; text-align: right">S/. {{ number_format($total, 2) }}</td>
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
        <td class="td-custom" style="width: 562.5px; border-left: none">: {{ $project->project_quote->delivery_time}} día(s)</td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 140px; border-right: none">Lugar de entrega</td>
        <td class="td-custom" style="width: 562.5px; border-left: none">
          : {{$project->project_quote->delivery_place}}
        </td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 140px; border-right: none">Forma de Pago</td>
        <td class="td-custom" style="width: 562.5px; border-left: none">: Segun Acuerdo Comercial</td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 140px; border-right: none">Validez de la oferta</td>
        <td class="td-custom" style="width: 562.5px; border-left: none">: 30 día(s)</td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 140px; height: 50px; border-right: none">Observaciones</td>
        <td class="td-custom" style="width: 562.5px; border-left: none">: {{$project->project_quote->observations}}</td>
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