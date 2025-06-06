<br>
<h1>Trabajadores - Datos de Ingresos, Tributos  y Aportes</h1>
<br>
<br>
<br>
<table>
    <tr>
        <td colspan="5">RUC : 20559246272</td>
    </tr>
    <tr>
        <td colspan="5">Empleador : CORPORACION DE CONTRATISTAS Y PROVEEDORES GENERALES S.R.L. - CONPROCO S.R.L.</td>
      
    </tr>
    <tr>
        <td colspan="5">Periodo: {{ $payroll->month }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <td colspan="5">Datos del Trabajador</td>
            <td colspan="6">Información Declarada</td>
        </tr>
        <tr>
            <th>Tipo</th>
            <th>Número</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Sit.</th>
            <th>Ingresos Devengados</th>
            <th>Ingresos Pagados</th>
            <th>Descuentos</th>
			<th>Aportes del Trabajador</th>
            <th>Neto a pagar</th>
            <th>Aportes del empleador</th>
        </tr>
    </thead>
    <tbody>
        @foreach($spreadsheet as $item)
        <tr>
            <td>01</td>
            <td>{{ $item->employee->dni }}</td>
            <td>{{ $item->employee->lastname }}</td>
            <td>{{ $item->employee->name }}</td>
            <td>{{ 'Activo o Subsidiado' }}</td>
            <td>{{ $item->new_totals['income_accrued_total'] }}</td>
            <td>{{ $item->new_totals['income_paid_total'] }}</td>
            <td>{{ $item->new_totals['discount_total'] }}</td>
            <td>{{ $item->new_totals['employee_tac_total'] }}</td>
			<td>{{ $item->new_totals['net_pay'] }}</td>
            <td>{{ $item->new_totals['employer_tac_total'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
            <td>{{ $total["income_accrued_total"] }}</td>
            <td>{{ $total["income_paid_total"] }}</td>
            <td>{{ $total["discount_total"] }}</td>
            <td>{{ $total["employee_tac_total"] }}</td>
            <td>{{ $total["net_pay"] }}</td>
            <td>{{ $total["employer_tac_total"] }}</td>
        </tr>
    </tbody>
</table>
