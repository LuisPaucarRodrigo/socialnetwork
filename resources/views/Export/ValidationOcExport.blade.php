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
            <td> {{ $validationOC->project_name }} </td>
            <td> {{ $validationOC->project_code }} </td>
            <td> {{ $validationOC->cpe }} </td>
            <td> {{ $validationOC->cicsa_purchase_order?->oc_number }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->file_validation }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->materials_control }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->supervisor }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->warehouse }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->boss }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->liquidator }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->superintendent }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->validation_date }} </td>
            <td> {{ $validationOC->cicsa_purchase_order_validation?->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>