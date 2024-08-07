<table>
    <thead>
        <tr>
            @foreach($title as $item)
            <th> {{ $item }} </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($feasibilitys as $feasibility)
        <tr>
            <td> {{ $feasibility->project_name }} </td>
            <td> {{ $feasibility->project_code }} </td>
            <td> {{ $feasibility->cpe }} </td>
            <td> {{ $feasibility->cicsa_feasibility?->feasibility_date }} </td>
            <td> {{ $feasibility->cicsa_feasibility?->report }} </td>
            <td> {{ $feasibility->cicsa_feasibility?->coordinator }} </td>
            <td> {{ $feasibility->cicsa_feasibility?->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>