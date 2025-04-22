<table>
    <thead>
        <tr>
            @foreach($title as $item)
            <th> {{ $item }} </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($cicsa_charge_areas as $cicsa_charge_area)
            @if($cicsa_charge_area->cicsa_charge_area && $cicsa_charge_area->cicsa_charge_area->isNotEmpty())
                @foreach($cicsa_charge_area->cicsa_charge_area as $item)
                <tr>
                    <td> {{ $cicsa_charge_area->project_name }} </td>
                    <td> {{ $cicsa_charge_area->project->cost_center?->name }} </td>
                    <td> {{ $cicsa_charge_area->cpe }} </td>
                    <td> {{ $item->cicsa_purchase_order?->oc_number }} </td>
                    <td> {{ $item->invoice_number }} </td>
                    <td> {{ $item->invoice_date }} </td>
                    <td> {{ $item->credit_to }} </td>
                    <td> {{ $item->deposit_date }} </td>
                    <td> {{ $item->amount }} </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td> {{ $cicsa_charge_area->project_name }} </td>
                <td> {{ $cicsa_charge_area->project->cost_center?->name }} </td>
                <td> {{ $cicsa_charge_area->cpe }} </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>