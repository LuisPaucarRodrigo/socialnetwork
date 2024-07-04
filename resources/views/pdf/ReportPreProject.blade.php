<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        table {
            width: 100%;
        }

        .column {
            border: 1px solid #000;
            padding-top: 5px;
        }

        .centered-text {
            text-align: center;
        }

        .text-font-size {
            font-size: 12px;
        }

        .photo {
            text-align: center;
        }

        .photo img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>

<body>
    <div>
        <table>
            <tr>
                <td class="column" style="width:25%">
                    <p></p>
                </td>
                <td class="column centered-text" style="width:50%;font-weight:bold;">
                    <p>MEMORIA DESCRIPTIVA</p>
                </td>
                <td class="column centered-text" style="width:25%">
                    <p class="text-font-size" style="font-weight:bold;">SIG-PR20-F02-PER</p>
                    <p class="text-font-size">Fecha de Aplicación:09/05/2021</p>
                    <p class="text-font-size">Version:01</p>
                </td>
            </tr>
        </table>

        <section style="padding-top: 30px">
            <h4 class="centered-text">IGH, AV. VILLA HERMOSA</h4>
            <h6 style="padding-top: 20px;">A. OBJETIVOS</h6>
            <p>Diseño de fibra óptica para poder atender a nuestro cliente IGH desde el punto de conexión hasta su sede.</p>

            <h6 style="padding-top: 20px;">B. DESCRIPCIÓN DEL TRABAJO</h6>
            <p>El trabajo de instalación de fibra Óptica consiste en:
                Cable F.O proyectado, es partir del poste cercano al Cliente, haciendo recorrido vía aérea por 04 postes existentes,
                desde último poste ingresará a cliente aprovechando acometida para energía comercial, y recorrerá interiores hasta llegar a gabinete de cliente.</p>

            <h6 style="padding-top: 20px;">C. UBICACIÓN DE TRABAJO</h6>

            <p class="centered-text">Foto 01: Recorrido del Tramo</p>
            <img class="centered-text" src="" alt="Recorrido del Tramo">

            <h6 style="padding-top: 20px;">D. REPORTE FOTOGRÁFICO - Recorrido de Fibra</h6>
            @foreach ($codesWithStatus as $code)
            <p class="centered-text">{{$code->code->code}}</p>
            
            @foreach ($code->imagecodepreprojet as $image)
            <div class="photo">
                <img class="centered-text" src="image/imagereportpreproject/{{ $image->image }}" alt="{{ $image->description }}">
                <p class="centered-text" class="centered-text">Lat:{{ $image->lat }} Lon:{{ $image->lon }}</p>
                <p class="centered-text" class="centered-text">Foto{{ $loop->iteration }}: {{ $image->description }}</p>
            </div>
            @endforeach
            @endforeach
            <h6 style="padding-top: 20px;">F. CONCLUSIONES Y OBSERVACIONES</h6>
            <!-- Añadir contenido -->

            <h6 style="padding-top: 20px;">G. MATERIALES A UTILIZAR</h6>
            <!-- Añadir contenido -->
        </section>

        <section class="row mt-4">
            <div class="col-6">
                <h6 style="padding-top: 20px;">DATOS DE LA EMPRESA CONTRATISTA:</h6>
                <p>Nombre de empresa contratista:</p>
                <p>Fecha de Visita:</p>
                <p>Nombre del técnico:</p>
                <p>Celular del técnico: </p>
                <p>CODIGO ATC</p>
            </div>
            <div class="col-6">
                <h6 style="padding-top: 20px;">DATOS DEL CLIENTE</h6>
                <p>Nombre de cliente: -</p>
                <p>Celular de Contacto: -</p>
                <p>Nombre de contacto GTD: -</p>
                <p>Celular de Contacto GTD: -</p>
            </div>
        </section>

        <section>
            <h6 style="padding-top: 20px;">J. CONSIDERACIONES Y RESPONSABILIDADES DEL CLIENTE</h6>
            <p>Brindar facilidades de acceso al local.</p>
        </section>
    </div>

</body>

</html>