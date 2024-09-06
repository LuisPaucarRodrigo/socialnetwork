<table>
    <thead>
        <tr>
            @foreach($title as $item)
            <th> {{ $item }} </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($assignations as $assignation)
        <tr>
            <td> {{ $assignation->project_name }} </td>
            <td> {{ $assignation->project_code }} </td>
            <td> {{ $assignation->cpe }} </td>
            <td> {{ $assignation->cicsa_installation?->pext_date }} </td>
            <td> {{ $assignation->cicsa_installation?->pint_date }} </td>
            <td> {{ $assignation->cicsa_installation?->conformity }} </td>
            <td> {{ $assignation->cicsa_installation?->report }} </td>
            <td> {{ $assignation->cicsa_installation?->shipping_report_date }} </td>
            <td> {{ $assignation->cicsa_installation?->projected_amount }} </td>
            <td> {{ $assignation->cicsa_installation?->coordinator }} </td>
            <td> {{ $assignation->cicsa_installation?->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>