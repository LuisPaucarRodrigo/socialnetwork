<table>
    <thead>
        <tr>
            <th> Nombre de Proyecto </th>
            <th> Codigo de Proyecto </th>
            <th> CPE </th>
            <th> Fecha de OC </th>
            <th> Numero de OC </th>
            <th> Formato Maestro </th>
            <th> Item 3456 </th>
            <th> Presupuesto </th>
            <th> Encargado </th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseOrders as $purchaseOrder)
        <tr>
            <td> {{ $purchaseOrder->project_name }} </td>
            <td> {{ $purchaseOrder->project_code }} </td>
            <td> {{ $purchaseOrder->cpe }} </td>
            <td> {{ $purchaseOrder->cicsa_purchase_order->oc_date }} </td>
            <td> {{ $purchaseOrder->cicsa_purchase_order->oc_number }} </td>
            <td> {{ $purchaseOrder->cicsa_purchase_order->master_format }} </td>
            <td> {{ $purchaseOrder->cicsa_purchase_order->item3456 }} </td>
            <td> {{ $purchaseOrder->cicsa_purchase_order->budget }} </td>
            <td> {{ $purchaseOrder->cicsa_purchase_order->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>