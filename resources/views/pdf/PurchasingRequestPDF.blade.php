<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <title>{{ $purchasing_request->title }}</title>
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
  <table style="width: 715px">
    <tbody>
      <tr>
        <td class="td-custom" colspan="1" style="height: 70px;"><img src="image/projectimage/logo_ccip.jpeg" width="80px" alt=""></td>
        <td class="td-custom" colspan="2" style="text-align: center; font-weight: bold; font-size: 18px">{{ $purchasing_request->title }}</td>
        <td class="td-custom" colspan="1" style="text-align: center">{{ $purchasing_request->code }}</td>
      </tr>
      <tr>
        <td class="td-custom" style="width: 82.5px">Elaborado por:</td>
        <td class="td-custom" style="width: 150px" style="text-align: center; font-weight: bold;">CCIP</td>
        <td class="td-custom" style="width: 130px">Fecha</td>
        <td class="td-custom" style="width: 110px" style="text-align: right">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $purchasing_request->created_at)->format('d/m/Y') }}</td>
      </tr>
    </tbody>
  </table>

  <table class="table mt-3" style="width: 715px">
    <thead>
      <tr>
        <th class="td-custom" scope="col" colspan="4" style="text-align: center; background: #2e75b5; font-weight: normal;">Productos</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="td-custom" style="width: 60px; text-align: center; background: #2e75b5; font-weight: normal;">Partida</td>
        <td class="td-custom" style="width: 341px; text-align: center; background: #2e75b5; font-weight: normal;">Descripción</td>
        <td class="td-custom" style="width: 55px; text-align: center; background: #2e75b5; font-weight: normal;">Unidad</td>
        <td class="td-custom" style="width: 67px; text-align: center; background: #2e75b5; font-weight: normal;">Cantidad</td>
      </tr>
      @foreach ($purchasing_request->purchasing_request_product as $index => $item)
      <tr>

        <td class="td-custom" style="text-align: right">1.0{{ $index+1 }}</td>
        <td class="td-custom">{{ $item->purchase_product->name }}</td>
        <td class="td-custom" style="text-align: center">{{ $item->purchase_product->unit }}</td>
        <td class="td-custom" style="text-align: center">{{ $item->quantity }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>


</body>


</html>