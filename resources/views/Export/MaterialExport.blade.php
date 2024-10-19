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
            <td> {{ $assignation->assignation_date }} </td>
            <td> {{ $assignation->project_name }} </td>
            <td> {{ $assignation->cost_center }} </td>
            <td> {{ $assignation->customer }} </td>
            <td> {{ $assignation->project_code }} </td>
            <td> {{ $assignation->cpe }} </td>
            <td> {{ $assignation->project_deadline }} </td>
            <td> {{ $assignation->manager }} </td>
            <td> {{ $assignation->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>