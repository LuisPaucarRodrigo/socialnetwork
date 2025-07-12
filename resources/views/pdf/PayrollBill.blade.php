<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 8px;
            line-height: 1.1;
        }

        .container {
            width: 100%;
            max-width: 800px;
        }

        .header-grid {
            display: table;
            width: 100%;
            font-size: 9px;
            line-height: 1;
            margin-bottom: 6px;
        }

        .header-row {
            display: table-row;
        }

        .header-left {
            display: table-cell;
            text-align: left;
            font-size: 11px;
            width: 70%;
            padding: 1px 0;
        }

        .header-right {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            width: 30%;
            padding: 1px 0;
        }

        .title {
            font-size: 17px;
            font-weight: normal;
        }

        .company-info {
            border: 1px solid #949c9c;
            background-color: #c8dcf4;
            padding: 1px 1px;
            font-size: 11px;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .company-info div {
            margin: 1px 0;
        }

        .plame-row {
            display: table;
            width: 100%;
            margin-top: 2px;
        }

        .plame-left,
        .plame-right {
            display: table-cell;
            width: 50%;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 8px;
            table-layout: fixed;
        }

        .main-table td,
        .main-table th {
            border: 1px solid black;
            text-align: center;
            padding: 2px 1px;
            vertical-align: middle;
            font-size: 10px;
            width: 12.5%;
        }

        .header-cell {
            background-color: #e0e4f4;
            font-weight: normal;
            white-space: nowrap;
        }

        .data-cell {
            background-color: white;
        }

        .concepts-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }

        .concepts-table td,
        .concepts-table th {
            border: 1px solid black;
            padding: 2px 3px;
            text-align: left;
            font-size: 10px;
        }

        .concepts-table th {
            background-color: #e0e4f4;
            text-align: center;
            font-weight: normal;
        }

        .amount-right {
            text-align: right;
        }

        .section-header {
            background-color: #e0e4f4;
            font-weight: bold;
            font-size: 9px;
        }

        .custom-row {
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-top: none;
            border-bottom: none;
        }

        .total-row {
            background-color: #e0e4f4;
            font-weight: bold;
        }

        .employer-contributions {
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-grid">
            <div class="header-row">
                <div class="header-left title">R08: Trabajador – Datos de boleta de pago</div>
                <div class="header-right">Página 1</div>
            </div>
            <div class="header-row">
                <div class="header-left">(Contiene datos mínimos de una boleta de pago)</div>
                <div class="header-right">{{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </div>
            </div>
            <div class="header-row">
                <div class="header-left"></div>
                <div class="header-right">
                    {{ \Carbon\Carbon::now()->format('H:i:s') }}
                </div>
            </div>
        </div>

        <div style="margin-top: 40px;" class="company-info">
            <div>RUC: 20559246272</div>
            <div>Empleador: CORPORACION DE CONTRATISTAS Y PROVEEDORES GENERALES S.R.L. CONPROCO S.R.L.</div>
            <div>Periodo: {{$spreadsheet['month']}}</div>
            <div class="plame-row">
                <div class="plame-left">PDT Planilla Electrónica - PLAME</div>
                <div class="plame-right">Número de Orden:</div>
            </div>
        </div>

        <table class="main-table" style="margin-top: 40px; border-collapse: collapse; width: 100%;">
            <tr>
                <td colspan="2" class="header-cell">Documento de Identidad</td>
                <td class="header-cell" style="border-right: none; border-bottom: none;"></td>
                <td class="header-cell" style="border-right: none; border-left: none; border-bottom: none;"></td>
                <td class="header-cell" style="border-right: none; border-left: none; border-bottom: none;"></td>
                <td class="header-cell" style="border-left: none; border-bottom: none;"></td>
                <td class="header-cell" style="border-right: none; border-left: none; border-bottom: none;"></td>
                <td class="header-cell" style="border-left: none; border-bottom: none;"></td>
            </tr>

            <tr>
                <td class="header-cell">Tipo</td>
                <td class="header-cell">Número</td>
                <td colspan="4" class="header-cell" style="border-top: none;">Nombres y Apellidos</td>
                <td colspan="2" class="header-cell" style="border-top: none;">Situación</td>
            </tr>
            <tr>
                <td class="data-cell">DNI</td>
                <td class="data-cell">{{$spreadsheet['dni']}}</td>
                <td colspan="4" class="data-cell">{{$spreadsheet['name']}}</td>
                <td colspan="2" class="data-cell">ACTIVO O SUBSIDIADO</td>
            </tr>
            <tr>
                <td colspan="2" class="header-cell">Fecha de Ingreso</td>
                <td colspan="2" class="header-cell">Tipo de Trabajador</td>
                <td colspan="2" class="header-cell">Régimen Pensionario</td>
                <td colspan="2" class="header-cell">CUSPP</td>
            </tr>
            <tr>
                <td colspan="2" class="data-cell">{{$spreadsheet['hire_date']}}</td>
                <td colspan="2" class="data-cell">EMPLEADO</td>
                <td colspan="2" class="data-cell">{{$spreadsheet['pension_type']}}</td>
                <td colspan="2" class="data-cell">{{$spreadsheet['cuspp']}}</td>
            </tr>
            <tr>
                <td rowspan="2" class="header-cell">Días<br>Laborados</td>
                <td rowspan="2" class="header-cell">Días No<br>Laborados</td>
                <td rowspan="2" class="header-cell">Días<br>Subsidiados</td>
                <td rowspan="2" class="header-cell">Condición</td>
                <td colspan="2" class="header-cell">Jornada Ordinaria</td>
                <td colspan="2" class="header-cell">Sobretiempo</td>
            </tr>
            <tr>
                <td class="header-cell">Total Horas</td>
                <td class="header-cell">Minutos</td>
                <td class="header-cell">Total Horas</td>
                <td class="header-cell">Minutos</td>
            </tr>
            <tr>
                <td class="data-cell">{{$spreadsheet['days_worked']}}</td>
                <td class="data-cell">{{$spreadsheet['days_not_worked']}}</td>
                <td class="data-cell">{{$spreadsheet['days_subsided']}}</td>
                <td class="data-cell">Domiciliado</td>
                <td class="data-cell">{{$spreadsheet['total_ordinary_hours']}}</td>
                <td class="data-cell">{{$spreadsheet['total_ordinary_minutes']}}</td>
                <td class="data-cell">{{$spreadsheet['total_overtime_hours']}}</td>
                <td class="data-cell">{{$spreadsheet['total_overtime_minutes']}}</td>
            </tr>
            <tr>
                <td colspan="6" class="header-cell">Motivo de Suspensión de Labores</td>
                <td colspan="2" rowspan="2" class="header-cell">Otros empleadores por<br>Rentas de 5ta categoría</td>
            </tr>
            <tr>
                <td class="header-cell">Tipo</td>
                <td colspan="4" class="header-cell">Motivo</td>
                <td class="header-cell">N.º Días</td>
            </tr>
            <tr>
                <td class="data-cell"></td>
                <td colspan="4" class="data-cell"></td>
                <td class="data-cell"></td>
                <td colspan="2" class="data-cell">No tiene</td>
            </tr>
        </table>

        <table class="concepts-table" style="margin-top: 40px;">
            <tr>
                <th style="width: 12%;">Código</th>
                <th style="width: 48%;">Conceptos</th>
                <th style="width: 13%;">Ingresos S/.</th>
                <th style="width: 13%;">Descuentos S/.</th>
                <th style="width: 14%;">Neto S/.</th>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
            </tr>

            <tr>
                <td style="font-weight: normal; border-top: none; border-bottom: none;" colspan="5"
                    class="section-header">Ingresos</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
            </tr>

            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0121</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">REMUNERACIÓN O
                    JORNAL BÁSICO</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
                    class="amount-right">{{number_format($spreadsheet['basic_salary'],2)}}</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0904</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">COMPENSACIÓN
                    TIEMPO DE SERVICIOS</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
                    class="amount-right">{{number_format($spreadsheet['cts'],2)}}</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0914</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">REFRIGER. QUE
                    NO ES ALIMENT.PRINCIP</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
                    class="amount-right">{{number_format($spreadsheet['rnp_amount'],2)}}</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
            </tr>

            <tr>
                <td colspan="5" class="section-header"
                    style="font-weight: normal; border-top: none; border-bottom: none;">Descuentos</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
            </tr>

            <tr>
                <td colspan="5" class="section-header"
                    style="font-weight: normal; border-top: none; border-bottom: none;">Aportes del Trabajador</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
            </tr>

            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0601</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">COMISIÓN AFP
                    PORCENTUAL</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
<<<<<<< HEAD
                    class="amount-right">{{number_format($spreadsheet['cp_amount'],2)}}</td>
=======
                    class="amount-right">0.00</td>
>>>>>>> origin/huawei_data
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0605</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">RENTA QUINTA
                    CATEGORÍA RETENCIONES</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
                    class="amount-right">{{number_format($spreadsheet['rq_amount'],2)}}</td>
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0606</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">PRIMA DE
                    SEGURO AFP</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
                    class="amount-right">{{ number_format($spreadsheet['ps_amount'],2) }}</td>
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-top: none; border-right: none;">0608</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">SPP -
                    APORTACIÓN OBLIGATORIA</td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none; text-align: right;"
                    class="amount-right">{{ number_format($spreadsheet['ap_amount'],2) }}</td>
                <td style="border-bottom: none; border-top: none; border-left: none;"></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
            </tr>

            <tr class="total-row">
                <td style="border-bottom: none; border-top: none; border-right: none; font-weight: normal;">Neto a Pagar
                </td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                <td style="border-bottom: none; font-weight: normal; border-top: none; border-left: none; text-align: right; vertical-align: bottom;"
                    class="amount-right">
                    {{number_format($spreadsheet['net_pay'],2)}}
                </td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0.1px; border-top: none;"></td>
            </tr>
        </table>

        <div class="employer-contributions" style="margin-top: 40px;">
            <table class="concepts-table">
                <tr>
                    <td colspan="5" class="section-header" style="font-weight: normal">Aportes de Empleador</td>
                </tr>
                <tr>
                    <td colspan="5" style="height: 0.1px; border-top: none; border-bottom: none;"></td>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none; border-right: none;">0803</td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;">PÓLIZA DE
                        SEGURO - D. LEG. 688</td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;"></td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;"></td>
                    <td style="border-top: none; border-bottom: none; border-left: none; text-align: right;"
                        class="amount-right">5.31</td>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none; border-right: none;">0804</td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;">
                        ESSALUD(REGULAR CBSSP AGRAR/AC)TRAB</td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;"></td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;"></td>
                    <td style="border-top: none; border-bottom: none; border-left: none; text-align: right;"
                        class="amount-right">{{number_format($spreadsheet['eSalud'], 2) }}</td>
                </tr>
                <tr>
                    <td style="border-top: none; border-bottom: none; border-right: none;">0805</td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;">SCTR
                        PENSIONES</td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;"></td>
                    <td style="border-top: none; border-bottom: none; border-left: none; border-right: none;"></td>
                    <td style="border-top: none; border-bottom: none; border-left: none; text-align: right;"
                        class="amount-right">10.74</td>
                </tr>
                <tr>
                    <td style="border-bottom: none; border-top: none; border-right: none;">0810</td>
                    <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;">EPS -
                        SEGURO COMPLEMENTARIO DE
                        TRAB</td>
                    <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                    <td style="border-bottom: none; border-top: none; border-left: none; border-right: none;"></td>
                    <td style="border-bottom: none; border-top: none; border-left: none; text-align: right;"
                        class="amount-right">9.04</td>
                </tr>
                <tr>
                    <td colspan="5" style="height: 0.1px; border-top: none;"></td>
                </tr>

            </table>
        </div>

    </div>
</body>

</html>