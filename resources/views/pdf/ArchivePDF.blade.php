<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $archive->name }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="text-center">
            <img src="image/projectimage/logo_ccip.jpeg" width="150px" alt="" class="img-fluid">
        </div>
        <div>
            <h2 class="mt-5 text-center max-width small-header" style="font-size: 24px;">GUIA</h2>
            <h2 class="text-center max-width small-header" style="font-size: 24px;">ELABORACIÓN DE INFORMACIÓN DOCUMENTADA</h2>
        </div>

        <br>
        <br>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000;">Código:</td>
                    <td colspan="2" style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $archive->code_archive }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000;">Nombre:</td>
                    <td colspan="2" style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $archive->name . '.' . $archive->extension }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000;">Versión N°:</td>
                    <td colspan="2" style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $archive->version }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000;">Fecha de Elaboración:</td>
                    <td colspan="2" style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $archive->created_at->format('d/m/y') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000;">Elaborado por:</td>
                    <td colspan="2" style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $archive->previous_archive->user->name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid #000; text-align: center">ACTUALIZADO POR:</td>
                    <td style="font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid #000; text-align: center">REVISADO POR:</td>
                    <td style="font-weight: bold; font-size: 14px; padding: 4px; border: 1px solid #000; text-align: center">APROBADO POR:</td>
                </tr>
                <tr>
                    <td style="height: 200px; padding: 2px; border: 1px solid #000;;"><p style="margin-top: 15px">Firma:</p></td>
                    <td style="border: 1px solid #000; padding: 2px; height: 230px;">
                        <ul style="margin: 0; padding-left: 0; list-style-type: none; text-align: left; margin-top: 15px"> <!-- Elimina las viñetas y centra el texto -->
                            @foreach($archive->previous_archive->users as $index => $user) <!-- Itera sobre todos los usuarios -->
                                <li style="padding: 0; margin-bottom: {{ $index === count($archive->previous_archive->users) - 1 ? '0' : '45px' }}">Firma:</li> <!-- Renderiza el nombre de cada usuario -->
                            @endforeach
                        </ul>
                    </td>
                    <td style="border: 1px solid #000; padding: 2px;">
                        <ul style="margin: 0; padding-left: 0; list-style-type: none; text-align: left; margin-top: 15px"> <!-- Elimina las viñetas y centra el texto -->
                            @foreach($archive->previous_archive->users as $index => $user) <!-- Itera sobre todos los usuarios -->
                                <li style="padding: 0; margin-bottom: {{ $index === count($archive->previous_archive->users) - 1 ? '0' : '45px' }}">Firma:</li> <!-- Renderiza el nombre de cada usuario -->
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; padding: 2px; text-align: center;">{{ $archive->user->name }}</td>
                    <td style="border: 1px solid #000; padding: 2px;"><ul style="margin: 0; padding-left: 0; list-style-type: none; text-align: center;"> <!-- Elimina las viñetas y centra el texto -->
                            @foreach($archive->previous_archive->users as $user) <!-- Itera sobre todos los usuarios -->
                                <li style="padding: 0;">{{ $user->name }}</li> <!-- Renderiza el nombre de cada usuario -->
                            @endforeach
                        </ul></td>
                    <td style="border: 1px solid #000; padding: 2px;">
                        <ul style="margin: 0; padding-left: 0; list-style-type: none; text-align: center;"> <!-- Elimina las viñetas y centra el texto -->
                            @foreach($archive->previous_archive->users as $user) <!-- Itera sobre todos los usuarios -->
                                <li style="padding: 0;">{{ $user->name }}</li> <!-- Renderiza el nombre de cada usuario -->
                            @endforeach
                        </ul>
                    </td>

                </tr>
                <tr>
                    <td style="border: 1px solid #000; font-size: 12px; padding: 4px;">Fecha: {{ $archive->created_at->format('d/m/y') }}</td>
                    <td style="border: 1px solid #000; font-size: 12px; padding: 4px;">Fecha: {{ $archive->created_at->format('d/m/y') }}</td>
                    <td style="border: 1px solid #000; font-size: 12px; padding: 4px;">Fecha: {{ $archive->created_at->format('d/m/y') }}</td>
                </tr>
            </tbody>
        </table>

<!-- Nueva tabla -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000; width: 100px; text-align: center;">Versión</th>
            <th style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000; text-align: center;">Descripción de Cambios</th>
            <th style="font-weight: bold; font-size: 12px; padding: 4px; border: 1px solid #000; text-align: center;">Fecha</th>
        </tr>
    </thead>
    <tbody>
        <!-- Fila para la información del archivo actual -->
        <tr>
            <td style="border: 1px solid #000; font-size: 12px; padding: 4px; text-align: center;">{{ $archive->version }}</td>
            <td style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $archive->comment }}</td>
            <td style="border: 1px solid #000; font-size: 12px; padding: 4px; text-align: center;">{{ $archive->created_at->format('d/m/y') }}</td>
        </tr>

        <!-- Filas para la información de los archivos anteriores -->
        @foreach($previous as $prev)
            <tr>
                <td style="border: 1px solid #000; font-size: 12px; padding: 4px; text-align: center;">{{ $prev->version }}</td>
                <td style="border: 1px solid #000; font-size: 12px; padding: 4px;">{{ $prev->comment }}</td>
                <td style="border: 1px solid #000; font-size: 12px; padding: 4px; text-align: center;">{{ $prev->created_at->format('d/m/y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



    </div>
</body>
</html>
