<table>
    <thead>
        <tr>
            <th> Nombre de Proyecto </th>
            <th> Codigo de Proyecto </th>
            <th> CPE </th>
            <th> Fecha de OC </th>
            <th> Numero de OC </th>
            <th> Monto sin IGV </th>
            <th> Formato Maestro </th>
            <th> Item 3456 </th>
            <th> Presupuesto </th>
            <th> Monto Proyectado sin IGV en Instalación</th>
            <th> Encargado </th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchaseOrders as $purchaseOrder)
        <tr>
            <td> {{ $purchaseOrder->cicsa_assignation->project_name }} </td>
            <td> {{ $purchaseOrder->cicsa_assignation->project_code }} </td>
            <td> {{ $purchaseOrder->cicsa_assignation->cpe }} </td>
            <td> {{ $purchaseOrder?->oc_date }} </td>
            <td> {{ $purchaseOrder?->oc_number }} </td>
            <td> {{ $purchaseOrder?->amount }} </td>
            <td> {{ $purchaseOrder?->master_format }} </td>
            <td> {{ $purchaseOrder?->item3456 }} </td>
            <td> {{ $purchaseOrder?->budget }} </td>
            <td> {{ $purchaseOrder?->cicsa_assignation->cicsa_installation?->projected_amount }} </td>
            <td> {{ $purchaseOrder?->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>