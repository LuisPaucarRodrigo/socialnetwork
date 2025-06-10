<br>
<h1>Trabajadores - Datos Detallados</h1>
<br>
<br>
<br>
<table>
    <tr>
        <td colspan="10">RUC : 20559246272</td>
    </tr>
    <tr>
        <td colspan="10">Empleador : CORPORACION DE CONTRATISTAS Y PROVEEDORES GENERALES S.R.L. - CONPROCO S.R.L.</td>
      
    </tr>
    <tr>
        <td colspan="10">Periodo: {{ $payroll->month }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <td colspan="5">Datos del Trabajador</td>
            @if ($incomes && $incomes->isNotEmpty())
                <td colspan="{{ 2*$incomes->count() }}">Detalle de Ingresos</td>
            @endif
            @if ($discounts && $discounts->isNotEmpty())
                <td colspan="{{ $discounts->count() }}">Detalle de Descuentos</td>
            @endif
            @if ($tacEmployee && $tacEmployee->isNotEmpty())
                <td colspan="{{ $tacEmployee->count() }}">Detalles de Aporte de Trabajador</td>
            @endif
            @if ($tacEmployer && $tacEmployer->isNotEmpty())
                <td colspan="{{ $tacEmployer->count() }}">Detalles de Aporte Empleador</td>
            @endif

        </tr>
        <tr>
            <th rowspan="2">Tipo</th>
            <th rowspan="2">Número</th>
            <th rowspan="2">Apellidos</th>
            <th rowspan="2">Nombres</th>
            <th rowspan="2">Sit.</th>
            @if ($incomes  && $incomes->isNotEmpty())
                @foreach ( $incomes as $inc )
                    <th colspan="2">{{ $inc->concept  }}</th>
                @endforeach
            @endif
            @if ($discounts && $discounts->isNotEmpty())
                @foreach ( $discounts as $disc )
                    <th rowspan="2">{{ $disc->concept  }}</th>
                @endforeach
            @endif
            @if ($tacEmployee && $tacEmployee->isNotEmpty())
                @foreach ( $tacEmployee as $tacemp )
                    <th colspan="2">{{ $tacemp->concept  }}</th>
                @endforeach
            @endif
            @if ($tacEmployer && $tacEmployer->isNotEmpty())
                @foreach ( $tacEmployee as $tacemp )
                    <th colspan="2">{{ $tacemp->concept  }}</th>
                @endforeach
            @endif
        </tr>
        <tr>
            @if ($incomes)
                @foreach ( $incomes as $inc )
                    <th>Devengados</th>
                    <th>Pagados</th>
                @endforeach
            @endif
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
            @if ($incomes?->isNotEmpty())
                @foreach ($incomes as $inc)
                    @php
                        $incomeData = $item->monetary_incomes_by_ids[$inc->id] ?? null;
                    @endphp
                    <td>{{ $incomeData['accrued_amount'] ?? '' }}</td>
                    <td>{{ $incomeData['paid_amount'] ?? '' }}</td>
                @endforeach
            @endif
            @if ($discounts?->isNotEmpty())
                @foreach ($discounts as $disc)
                    @php
                        $discountData = $item->monetary_discounts_by_ids[$disc->id] ?? null;
                    @endphp
                    <td>{{ $discountData['amount'] ?? '' }}</td>
                @endforeach
            @endif
            @if ($tacEmployee?->isNotEmpty())
                @foreach ($tacEmployee as $tacemp)
                    @php
                        $tacemployeeData = $item->tax_contribution_employee_by_ids[$tacemp->id] ?? null;
                    @endphp
                    <td>{{ $tacemployeeData['amount'] ?? '' }}</td>
                @endforeach
            @endif
            @if ($tacEmployer?->isNotEmpty())
                @foreach ($tacEmployer as $tacemp)
                    @php
                        $tacemployerData = $item->tax_contribution_employer_by_ids[$tacemp->id] ?? null;
                    @endphp
                    <td>{{ $tacemployerData['amount'] ?? '' }}</td>
                @endforeach
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
