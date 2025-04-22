<table>
    <thead>
        <tr>
            <th> Nombre de Proyecto </th>
            <th> Centro de Costos </th>
            <th> Monto Proyectado sin IGV en Instalación</th>
            <th> Fecha de Envio de Informe </th>
            <th> CPE </th>
            <th> Fecha de OC </th>
            <th> Numero de OC </th>
            <th> Monto sin IGV </th>
            <th> Formato Maestro </th>
            <th> Item 3456 </th>
            <th> Presupuesto </th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseOrders as $purchaseOrder)
        @if($purchaseOrder->cicsa_purchase_order && $purchaseOrder->cicsa_purchase_order->isNotEmpty())
        @foreach($purchaseOrder->cicsa_purchase_order as $item)
        <tr>
            <td> {{ $purchaseOrder->project_name }} </td>
            <td> {{ $purchaseOrder->project->cost_center->name }} </td>
            <td> {{ $purchaseOrder?->cicsa_installation?->projected_amount }} </td>
            <td> {{ $purchaseOrder?->cicsa_installation?->shipping_report_date }} </td>
            <td> {{ $purchaseOrder->cpe }} </td>
            <td> {{ $item?->oc_date }} </td>
            <td> {{ $item?->oc_number }} </td>
            <td> {{ $item?->amount }} </td>
            <td> {{ $item?->master_format }} </td>
            <td> {{ $item?->item3456 }} </td>
            <td> {{ $item?->budget }} </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td> {{ $purchaseOrder->project_name }} </td>
            <td> {{ $purchaseOrder->project->cost_center->name }} </td>
            <td> {{ $purchaseOrder?->cicsa_installation?->projected_amount }} </td>
            <td> {{ $purchaseOrder?->cicsa_installation?->shipping_report_date }} </td>
            <td> {{ $purchaseOrder->cpe }} </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>