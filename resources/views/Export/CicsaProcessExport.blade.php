@php
$materialFields = ['pick_date', 'guide_number', 'user_name'];

$OCFields = ['oc_date', 'oc_number', 'master_format', 'item3456', 'budget', 'user_name'];

$validationOCFields = [
'file_validation', 'materials_control', 'supervisor', 'warehouse', 'boss',
'liquidator', 'superintendent', 'observations', 'validation_date', 'user_name'
];

$serviceOrderFields = [
'service_order_date', 'service_order', 'estimate_sheet',
'purchase_order', 'pdf_invoice', 'zip_invoice', 'user_name'
];

$chargeAreaFields = ['cicsa_purchase_order.oc_number',
'invoice_number', 'invoice_date', 'credit_to',
'deposit_date', 'amount', 'user_name'
];
@endphp
<table>
    <thead>
        <tr>
            @foreach($title as $item)
            <th> {{ $item }} </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($cicsaProcess as $item)
        <tr>
            <td> {{ $item->assignation_date }} </td>
            <td> {{ $item->project_name }} </td>
            <td> {{ $item->cost_center }} </td>
            <td> {{ $item->customer }} </td>
            <td> {{ $item->project_code }} </td>
            <td> {{ $item->cpe }} </td>
            @if($stages === 'Proyecto' || $stages === 'Todos')
            <td> {{ $item->manager }} </td>
            <td> {{ $item->user_name }} </td>

            <td> {{ $item->cicsa_feasibility?->feasibility_date }} </td>
            <td> {{ $item->cicsa_feasibility?->report }} </td>
            <td> {{ $item->cicsa_feasibility?->coordinator }} </td>
            <td> {{ $item->cicsa_feasibility?->user_name }} </td>

            @foreach($materialFields as $field)
            <td>
                @if($item->cicsa_materials)
                @foreach($item->cicsa_materials as $materials)
                <p>{{ $materials->$field }}</p>
                @endforeach
                @endif

            </td>
            @endforeach

            <td> {{ $item->cicsa_installation?->pext_date }} </td>
            <td> {{ $item->cicsa_installation?->pint_date }} </td>
            <td> {{ $item->cicsa_installation?->conformity }} </td>
            <td> {{ $item->cicsa_installation?->report }} </td>
            <td> {{ $item->cicsa_installation?->shipping_report_date }} </td>
            <td> {{ $item->cicsa_installation?->projected_amount }} </td>
            <td> {{ $item->cicsa_installation?->coordinator }} </td>
            <td> {{ $item->cicsa_installation?->user_name }} </td>
            @endif
            @if($stages === 'Administracion' || $stages === 'Todos')
            @foreach($OCFields as $field)
            <td>
                @if($item->cicsa_purchase_order)
                @foreach($item->cicsa_purchase_order as $order)
                <p>{{ $order->$field ?? '--' }}</p>
                @endforeach
                @endif

            </td>
            @endforeach

            @foreach($validationOCFields as $field)
            <td>
                @foreach($item->cicsa_purchase_order_validation as $validationOC)
                <p>{{ $validationOC->$field ?? '--' }}</p>
                @endforeach
            </td>
            @endforeach

            @foreach($serviceOrderFields as $field)
            <td>
                @foreach($item->cicsa_service_order as $serviceOrder)
                <p>{{ $serviceOrder->$field ?? '--' }}</p>
                @endforeach
            </td>
            @endforeach
            @endif
            @if($stages === 'Cobranza' || $stages === 'Todos')          
            @foreach($chargeAreaFields as $field)
            <td>
                @foreach($item->cicsa_charge_area as $chargeArea)
                <p>{{ data_get($chargeArea, $field, '--') }}</p>
                @endforeach
            </td>
            @endforeach
            @endif
        </tr>
        @endforeach
    </tbody>
</table>