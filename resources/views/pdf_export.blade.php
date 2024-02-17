<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla con Divs</title>
    <style>
        .table {
            display: table;
            border-collapse: collapse;
            width: 100%;
        }

        .table-row {
            display: table-row;
        }

        .table-cell {
            display: table-cell;
            border: 1px solid #000 ;
            padding: 8px;
        }

        .table-cell-header {
            height: 40px; /* Ajusta la altura del encabezado según tus necesidades */
        }
    </style>
</head>
<body>

<div class="table">
    <div class="table-row">
        <div class="table-cell table-cell-header">Encabezado 1</div>
        <div class="table-cell table-cell-header">Encabezado 2</div>
        <div class="table-cell table-cell-header">Encabezado 3</div>
    </div>
    <div class="table-row">
        <div class="table-cell">Fila 1, Celda 1</div>
        <div class="table-cell">Fila 1, Celda 2</div>
        <div class="table-cell">Fila 1, Celda 3</div>
    </div>
    <div class="table-row">
        <div class="table-cell">Fila 2, Celda 1</div>
        <div class="table-cell">Fila 2, Celda 2</div>
        <div class="table-cell">Fila 2, Celda 3</div>
    </div>
    <div class="table-row">
        <div class="table-cell">Fila 3, Celda 1</div>
        <div class="table-cell">Fila 3, Celda 2</div>
        <div class="table-cell">Fila 3, Celda 3</div>
    </div>
</div>

</body>
</html>
