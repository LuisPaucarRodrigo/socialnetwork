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
        <tr>
            <td> {{ $cicsa_charge_area->project_name }} </td>
            <td> {{ $cicsa_charge_area->project_code }} </td>
            <td> {{ $cicsa_charge_area->cpe }} </td>
            <td> {{ $cicsa_charge_area->cicsa_purchase_order?->oc_number }} </td>
            <td> {{ $cicsa_charge_area->cicsa_charge_area?->invoice_number }} </td>
            <td> {{ $cicsa_charge_area->cicsa_charge_area?->invoice_date }} </td>
            <td> {{ $cicsa_charge_area->cicsa_charge_area?->credit_to }} </td>
            <td> {{ $cicsa_charge_area->cicsa_charge_area?->deposit_date }} </td>
            <td> {{ $cicsa_charge_area->cicsa_charge_area?->amount }} </td>
            <td> {{ $cicsa_charge_area->cicsa_charge_area?->user_name }} </td>
        </tr>
        @endforeach
    </tbody>
</table>