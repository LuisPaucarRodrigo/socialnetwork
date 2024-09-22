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
        <tr>
            <td> {{ $validationOC->cicsa_assignation->project_name }} </td>
            <td> {{ $validationOC->cicsa_assignation->project_code }} </td>
            <td> {{ $validationOC->cicsa_assignation->cpe }} </td>
            <td> {{ $validationOC->cicsa_purchase_order->oc_number }} </td>
            <td> {{ $validationOC->file_validation }} </td>
            <td> {{ $validationOC->materials_control }} </td>
            <td> {{ $validationOC->supervisor }} </td>
            <td> {{ $validationOC->warehouse }} </td>
            <td> {{ $validationOC->boss }} </td>
            <td> {{ $validationOC->liquidator }} </td>
            <td> {{ $validationOC->superintendent }} </td>
            <td> {{ $validationOC->validation_date }} </td>
            <td> {{ $validationOC->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>