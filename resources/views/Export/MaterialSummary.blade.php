<table>
    <thead>
        <tr>
            <th>CPE</th>
            <th>CÓDIGO</th>
            <th>{{ $project->project_name }}</th>
            <th>UNIDAD</th>
            <th colspan="{{$materials_guides->count()}}">ORDEN DE TRABAJO</th>
            <th>TOTAL</th>
            <th colspan="2">MATERIAL</th>
            <th>DIFERENCIA</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @foreach($materials_guides as $item)
                <th>{{ $item->pick_date }}</th>
            @endforeach
            <th></th>
            <th colspan="2"></th>
            <th></th>

        </tr>
        <tr>
            <th>{{$project->cpe}}</th>
            <th></th>
            <th>DESCRIPCIÓN</th>
            <th></th>
            @foreach($materials_guides as $item)
                <th>{{ $item->guide_number }}</th>
            @endforeach
            <th></th>
            <th>MONTADO SEGÚN OC</th>
            <th>USADO</th>
            <th></th>

        </tr>
    </thead>
    <tbody>
        @foreach($materials as $item)
            <tr>
                
                <td> {{$project->cpe}} </td>
                <td> {{$item['code_ax']}} </td>
                <td> {{$item['name']}} </td>
                <td> {{$item['unit']}} </td>
                @foreach($materials_guides as $subItem)
                    <td>{{ isset($item[$subItem->id])
                    ? $item[$subItem->id]
                    : 0  }}
                    </td>
                @endforeach
                <td> {{ $item['total_quantity'] }} </td>
                <td> {{ $item['total_quantity'] }} </td>
                <td> {{ $item['used_quantity']  }} </td>
                <td> {{ $item['used_quantity'] !== null ? $item['total_quantity'] - $item['used_quantity'] : null }} </td>
            </tr>
        @endforeach
    </tbody>
</table>