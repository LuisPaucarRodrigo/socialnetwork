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
            <td> {{ $spreadsheet->contract->state }} </td>
            <td> {{ $spreadsheet->dni }} </td>
            <td> {{ $spreadsheet->name }} </td>
            <td> {{ $spreadsheet->contract->pension->type }} </td>
            <td> {{ $spreadsheet->contract->hire_date }} </td>
            <td> S/ {{ $spreadsheet->contract->basic_salary }} </td>
            <td> S/ {{ $spreadsheet->truncated_vacations }} </td>
            <td> S/ {{ $spreadsheet->total_income }} </td>
            <td> S/ {{ $spreadsheet->total_pension_base }} </td>
            <td> % {{ $spreadsheet->snp }} </td>
            <td> S/ {{ $spreadsheet->snp_onp }} </td>
            <td> % {{ $spreadsheet->commission }} </td>
            <td> S/ {{ $spreadsheet->commission_on_ra }} </td>
            <td> % {{ $spreadsheet->seg }} </td>
            <td> S/ {{ $spreadsheet->insurance_premium }} </td>
            <td> % {{ $spreadsheet->mandatory_contribution }} </td>
            <td> S/ {{ $spreadsheet->mandatory_contribution_amount }} </td>
            <td> S/ {{ $spreadsheet->total_discount }} </td>
            <td> S/ {{ $spreadsheet->net_pay }} </td>
            <td> S/ {{ $spreadsheet->total_pension_base }} </td>
            <td> S/ {{ $spreadsheet->healths }} </td>
            <td> S/ {{ $spreadsheet->life_ley }} </td>
            <td> S/ {{ $spreadsheet->total_contribution }} </td>
        </tr>
        @endforeach
    </tbody>
</table>