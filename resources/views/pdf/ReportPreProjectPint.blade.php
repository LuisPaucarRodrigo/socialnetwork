<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        table {
            width: 100%;
        }

        @page {
            margin: 100px 25px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0;
            right: 0;
            text-align: center;
            line-height: 35px;
        }

        header img {
            padding: 5px;
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
    <header>
        <table>
            <tr>
                <td class="column" style="text-align:center;">
                    <img src="image/cicsa_logo.jpg" alt="" class="img-fluid">
                </td>
                <td class="column centered-text" style="width:50%;font-weight:bold; font-size:18px">
                    <p>{{ strtoupper($preproject->description )}}</p>
                </td>
                <td class="column centered-text" style="width:25%">
                    <img src="image/claro_logo.png" width="70px" alt="" class="img-fluid">
                </td>
            </tr>
        </table>
    </header>
    <section style="display: flex; justify-content: center; padding-left: 100px">

        <article style="padding-top: 80px; width:80%;">
            <h6>
                1. DATOS DE INDENTIFICACIÓN
            </h6>
            <table style="margin-top:20px;">
                @foreach($identificationRows as $item)
                <tr>
                    <td class="column" style="font-weight:bold;">
                        <p style="margin-left: 5px">{{$item['first']}}</p>
                    </td>
                    <td class="column centered-text">
                        <p>{{$item['firstValue']}}</p>
                    </td>
                    <td class="column" style="font-weight:bold;">
                        <p style="margin-left: 5px">{{$item['second']}}</p>
                    </td>
                    <td class="column centered-text">
                        <p>{{$item['secondValue']}}</p>
                    </td>
                </tr>
                @endforeach
            </table>          
        </article>

        @foreach($preprojectImages->preprojectCodes as $imageCode)
        <article style="padding-top: 60px; width:80%;">
            <h6>
                {{$loop->index + 2}}. 
                {{strtoupper($imageCode->code->description)}}
            </h6>
            @foreach ($imageCode->imagecodepreprojet as $image)
                <div style="text-align:center; margin-top:20px;">
                    <div style="display: inline-block;">
                        <div class="photo">
                            <img 
                                class="centered-text" 
                                style="height: 400px" 
                                src="image/imagereportpreproject/{{ $image->image }}"
                                style="font-size:semibold"
                                alt="{{ $image->description }}">
                            <p 
                                class="centered-text" 
                                class="centered-text"
                                style="font-size:semibold"
                            >
                                Foto {{$loop->parent->index+1}} 
                                {{ $image->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </article>
        @endforeach

        <article>

        </article>






    </section>
</body>

</html>