<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive PDF</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agrega cualquier estilo personalizado aquí -->
    <style>
        /* Estilos personalizados */
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1.5px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Tabla de Archivo</h2>
        <table class="table">
            <tbody>
                <tr>
                    <td>Nombre:</td>
                    <td colspan="2">{{ $archive->name . '.' . $archive->extension }}</td>
                </tr>
                <tr>
                    <td>Versión N°:</td>
                    <td colspan="2">{{ $archive->version }}</td>
                </tr>
                <tr>
                    <td>Fecha de Elaboración:</td>
                    <td colspan="2">{{ $archive->created_at->format('d/m/y') }}</td>
                </tr>
                <tr>
                    <td>Elaborado por:</td>
                    <td colspan="2">{{ $archive->user->name }}</td>
                </tr>
                <tr>
                    <td style="width: 150px;">ACTUALIZADO POR:</td>
                    <td>OBSERVADO POR:</td>
                    <td>APROBADO POR:</td>
                </tr>
                <tr>
                    <td style="height: 200px;"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Columna 1</td>
                    <td>Columna 2</td>
                    <td>Columna 3</td>
                </tr>
                <tr>
                    <td>Columna 1</td>
                    <td>Columna 2</td>
                    <td>Columna 3</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
