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
            <td> {{ $assignation->cost_center }} </td>
            <td> {{ $assignation->cicsa_materials->pick_date }} </td>
            <td> {{ $assignation->cicsa_materials->guide_number }} </td>
            <td> {{ $assignation->cicsa_materials->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>