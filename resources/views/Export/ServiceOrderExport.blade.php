<table>
    <thead>
        <tr>
            @foreach($title as $item)
            <th> {{ $item }} </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($cicsa_service_orders as $cicsa_service_order)
        <tr>
            <td> {{ $cicsa_service_order->cicsa_assignation?->project_name }} </td>
            <td> {{ $cicsa_service_order->cicsa_assignation?->project_code }} </td>
            <td> {{ $cicsa_service_order->cicsa_assignation?->cost_center }} </td>
            <td> {{ $cicsa_service_order->cicsa_assignation?->cpe }} </td>
            <td> {{ $cicsa_service_order->cicsa_purchase_order?->oc_number }} </td>
            <td> {{ $cicsa_service_order->service_order_date }} </td>
            <td> {{ $cicsa_service_order->service_order }} </td>
            <td> {{ $cicsa_service_order->estimate_sheet }} </td>
            <td> {{ $cicsa_service_order->purchase_order }} </td>
            <td> {{ $cicsa_service_order->pdf_invoice }} </td>
            <td> {{ $cicsa_service_order->zip_invoice }} </td>
            <td> {{ $cicsa_service_order->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>