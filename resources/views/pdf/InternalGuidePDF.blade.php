<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guía Interna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            display: block;
        }
        .row {
            display: inline-block;
            vertical-align: top;
        }
        .column1 {
            width: 100px;
            margin-right: 25px;
            margin-top: 0px;
        }
        .column2 {
            width: 330px; /* Ancho fijo o relativo para el texto */
        }
        .column3 {
            width: 230px; /* Ancho fijo para la tabla */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 12px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .watermarked-section {
            position: relative;
            z-index: 1; /* Asegura que los contenidos estén sobre el fondo */

            /* Configuración del fondo */
            background-image: url('image/projectimage/logo_ccip.jpeg'); /* Ruta de la imagen */
            background-position: center; /* Centrar la imagen */
            background-repeat: no-repeat; /* No repetir la imagen */
            background-size: contain; /* Ajustar el tamaño de la imagen al contenedor */
            opacity: 1; /* Transparencia del fondo */
            width: 600px;
        }

        .watermarked-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 80px;
            right: 0;
            bottom: 500px;
            background-image: url('image/projectimage/logo_ccip.jpeg'); /* Ruta de la marca de agua */
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain; /* La imagen se ajusta al tamaño del contenedor */
            opacity: 0.1; /* Ajusta la opacidad de la marca de agua */
            z-index: -1; /* Asegura que la marca de agua esté detrás del contenido */
        }

        .table-container {
            max-height: 550px; /* Altura máxima de la tabla */
            overflow: hidden; /* Oculta las filas que exceden la altura */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px; /* Ajusta la altura del footer */
            z-index: -1; /* Mantén el footer detrás del contenido */
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            opacity: 1; /* Sin opacidad para la imagen */
            text-align: right;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 550px;
            left: 0;
            right: 0;
            bottom: 0;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            opacity: 0.1; /* Ajusta la opacidad de la marca de agua */
            z-index: -2; /* Asegura que la marca de agua esté detrás del contenido */
        }

        .footer hr {
            margin-top: 120px;
        }

        .footer p {
            color: red;
            font-size: 12px;
            margin-right: 30px;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Columna 1: Imagen -->
        <div class="row column1">
            <img src="image/projectimage/logo_ccip.jpeg" alt="Logo" width="130px">
        </div>

        <!-- Columna 2: Texto -->
        <div class="row column2">
            <p style="margin: 0; margin-bottom: 5px; font-weight: bold; font-size: 24px;">CONPROCO S.A.C.</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Implementación de radioenlaces de equipos Huawei, Ceragon, Nec, entre otros.</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Estudios técnicos (LOS) para factibilidad de radioenlaces.</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Implementación de Ultima Milla y Planta Interna fibra óptica.</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Configuración de Router Cisco, Huawei, Juniper, Teldat, switches a toda medida.</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Mantenimientos preventivos y correctivos de sistemas de radioenlaces y planta interna de fibra óptica.</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Montaje, fabricación de mástiles, escalerillas y torres livianas de telecomunicaciones para clientes corporativos</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Estudio TSSR e instalación de equipos de estaciones base celular (2G/3G/4G Equipos Huawei).</p>
            <p style="margin: 0; padding-left: 3px; font-size: 7px;">Instalaciones RF Indoor.</p>

        </div>

        <!-- Columna 3: Pequeña tabla -->
        <div class="row column3">
            <table>
                <tbody>
                    <tr>
                        <td style="font-weight: bold; text-align: center; font-size: 16px;">R.U.C. 20559246272</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background-color: #042070; color: white; text-align: center; font-size: 16px;">GUÍA DE REMISIÓN REMITENTE</td>
                    </tr>
                    <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        <div>
                            <p style="display: inline; margin: 0;">0001-</p>
                            <p style="display: inline; margin: 0; color: red;">{{ $code }}</p>
                        </div>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="text-align: center; font-size: 9px; font-weight: bold; margin-right: 250px;">
        <p style="margin: 0;">Calle Peral Nro. 119 Int. 102 - Arequipa - Arequipa - Arequipa</p>
        <p style="margin: 0;">Cel. +51 992 275 316</p>
        <p style="margin: 0;">E-mail: gflores@ccip.com.pe</p>
    </div>
    <div style="margin-top: 10px; display: block;">
    <div style="display: inline-block; width: 50%; vertical-align: top;">
        <table style="width: 100%; font-weight: bold; font-size: 9px; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="padding: 2px; border: none;">Fecha de Emisión:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $additionalData['emission_date'])->format('d/m/Y') }}</td>
                    <td style="padding: 2px; border: none;">Fecha de Traslado:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $additionalData['transfer_date'])->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Punto de Partida:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['start_point'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Punto de Llegada:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['end_point'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Destinatario:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['addresee'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">R.U.C.</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['ruc'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Unidad de Transporte y Conducir:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['transport_unit'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Marca:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['brand'] }}</td>
                    <td style="padding: 2px; border: none;">Placa:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['plate'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">N° de Licencia de Conducir</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['license'] }}
                    </td>
                    <td style="padding: 2px; border: none;">Control de Inscrip</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['inscrip_const'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Empresa de Transporte:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['transport_company'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
                <tr>
                    <td style="padding: 2px; border: none;">Nombre:</td>
                    <td style="padding: 2px; border: none; font-weight: normal;">{{ $additionalData['name'] }}</td>
                    <td style="padding: 2px; border: none;"></td>
                    <td style="padding: 2px; border: none;"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="display: inline-block; width: 49%; vertical-align: top;">
    <table style="width: 100%; border-collapse: collapse; font-weight: bold; font-size: 8px; border: none;">
        <tbody>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">1.- Venta</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">8.- Recojo de bienes transformados</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">2.- Venta Sujeta a Confirmar</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">9.- Emisor Itinerante</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">3.- Compra</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">10.- Zona Primaria</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">4.- Consignación</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">11.- Importación</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">5.- Devolución</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">12.- Exportación</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">6.- Traslado entre establecimientos de una misma empresa</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">13.- Venta con entrega a terceros</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">7.- Para Transformación</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">14.- Otros</td>
                <td style="padding: 2px; margin: 0; line-height: 1; border: none;">
                    <div style="width: 10px; height: 10px; border: 1px solid black; display: inline-block;"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</div>

<hr>

<table style="width: 100%; font-size: 9px;">
    <tr>
        <td style="width: 50%; border: none; padding: 0;">Material</td>
        <td style="width: 30%; border: none; padding: 0;">Serie</td>
        <td style="width: 10%; text-align: center; border: none; padding: 0;">Cantidad</td>
        <td style="width: 10%; text-align: center; border: none; padding: 0;">Unidad</td>
    </tr>
</table>

<hr style="border: none; border-top: 1.5px dotted black; width: 100%;">

<div class="watermarked-section">

</div>

<div class="footer">
    <hr>
    <p>DESTINATARIO</p>
</div>

<div class="table-container">
    <table style="width: 100%; font-size: 9px;">
        @foreach ($data as $index => $item)
            <tr>
                <td style="width: 50%; border: none; padding: 0;">{{ $item['name'] }}</td>
                <td style="width: 30%; border: none; padding: 0;">{{ $item['serie'] ? $item['serie'] : 'NO APLICA' }}</td>
                <td style="width: 10%; text-align: center; border: none; padding: 0;">{{ $item['quantity'] }}</td>
                <td style="width: 10%; text-align: center; border: none; padding: 0;">{{ $item['unit'] ? $item['unit'] : 'NIU' }}</td>
            </tr>
        @endforeach
    </table>
</div>

</body>
</html>
