<table>
    <thead>
        <tr>
            <th> Estado </th>
            <th> DNI </th>
            <th> Nombre </th>
            <th> REG.PEN </th>
            <th> Fecha Ingreso </th>
            <th> Sueldo </th>
            <th> Vac.Truncas </th>
            <th> Tot.Ingre. </th>
            <th> Tot.B.G.Sis.Pensionario </th>
            <th> % SNP </th>
            <th> SNP/ONP </th>
            <th> % COM </th>
            <th> %Com.Sobre R.A. </th>
            <th> % SEG </th>
            <th> PRIMA SEGURO </th>
            <th> % APORT. OBLIG. </th>
            <th> MONT. OBLIG. </th>
            <th> TOT. DESC. </th>
            <th> NETO PAGAR </th>
            <th> TOT.BASE GRAV. ESSAL. </th>
            <th> SALUD 9% </th>
            <th> VIDA LEY </th>
            <th> APORTE TOTAL </th>
        </tr>
    </thead>
    <tbody>
        @foreach($spreadsheets as $spreadsheet)
        <tr>
            <td> {{ $spreadsheet->state }} </td>
            <td> {{ $spreadsheet->employee->dni }} </td>
            <td> {{ $spreadsheet->employee->name }} </td>
            <td> {{ $spreadsheet->pension->type }} </td>
            <td> {{ $spreadsheet->hire_date }} </td>
            <td> S/ {{ number_format($spreadsheet->basic_salary,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->truncated_vacations,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->total_income,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->total_pension_base,2) }} </td>
            <td> % {{ number_format($spreadsheet->snp,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->snp_onp,2) }} </td>
            <td> % {{ number_format($spreadsheet->commission,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->commission_on_ra,2) }} </td>
            <td> % {{number_format($spreadsheet->seg,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->insurance_premium,2) }} </td>
            <td> % {{ number_format($spreadsheet->mandatory_contribution,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->mandatory_contribution_amount,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->total_discount,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->net_pay,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->total_pension_base,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->healths,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->life_ley,2) }} </td>
            <td> S/ {{ number_format($spreadsheet->total_contribution,2) }} </td>
        </tr>
        @endforeach
    </tbody>
</table>