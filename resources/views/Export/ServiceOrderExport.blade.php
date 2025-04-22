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
        @if($cicsa_service_order->cicsa_service_order && $cicsa_service_order->cicsa_service_order->isNotEmpty())
        @foreach($cicsa_service_order->cicsa_service_order as $item)
        <tr>
            <td> {{ $cicsa_service_order->project_name }} </td>
            <td> {{ $cicsa_service_order->project_code }} </td>
            <td> {{ $cicsa_service_order->project->cost_center->name }} </td>
            <td> {{ $cicsa_service_order->cpe }} </td>
            <td> {{ $item->cicsa_purchase_order?->oc_number }} </td>
            <td> {{ $item->service_order_date }} </td>
            <td> {{ $item->service_order }} </td>
            <td> {{ $item->estimate_sheet }} </td>
            <td> {{ $item->purchase_order }} </td>
            <td> {{ $item->pdf_invoice }} </td>
            <td> {{ $item->zip_invoice }} </td>
            <td> {{ $item->user_name }} </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td> {{ $cicsa_service_order->project_name }} </td>
            <td> {{ $cicsa_service_order->project_code }} </td>
            <td> {{ $cicsa_service_order->project->cost_center?->name }} </td>
            <td> {{ $cicsa_service_order->cpe }} </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>