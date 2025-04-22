<table>
    <thead>
        <tr>
            @foreach($title as $item)
            <th> {{ $item }} </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($cicsa_purchase_order_validations as $validationOC)
        @if($validationOC->cicsa_purchase_order_validation && $validationOC->cicsa_purchase_order_validation->isNotEmpty())
        @foreach($validationOC->cicsa_purchase_order_validation as $item)
        <tr>
            <td> {{ $validationOC->project_name }} </td>
            <td> {{ $validationOC->project_code }} </td>
            <td> {{ $validationOC->project->cost_center->name }} </td>
            <td> {{ $validationOC->cpe }} </td>
            <td> {{ $item->cicsa_purchase_order?->oc_number }} </td>
            <td> {{ $item->cicsa_purchase_order?->amount }} </td>
            <td> {{ $item->file_validation }} </td>
            <td> {{ $item->materials_control }} </td>
            <td> {{ $item->supervisor }} </td>
            <td> {{ $item->warehouse }} </td>
            <td> {{ $item->boss }} </td>
            <td> {{ $item->liquidator }} </td>
            <td> {{ $item->superintendent }} </td>
            <td> {{ $item->observations }} </td>
            <td> {{ $item->validation_date }} </td>
            <td> {{ $item->user_name }} </td>
            <td> {{ $validationOC->manager }} </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td> {{ $validationOC->project_name }} </td>
            <td> {{ $validationOC->project_code }} </td>
            <td> {{ $validationOC->project->cost_center?->name }} </td>
            <td> {{ $validationOC->cpe }} </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>