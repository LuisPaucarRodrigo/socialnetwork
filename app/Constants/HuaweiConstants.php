<?php

namespace App\Constants;

use App\Models\Employee;
use App\Models\ExternalEmployee;

class HuaweiConstants
{
    public static function getEmployees(): array
    {
        $employees = Employee::whereHas('contract.cost_line', function ($query) {
            $query->where('name', 'HUAWEI')
                ->orWhere('name', 'ADMINISTRATIVO');
        })
            ->whereHas('contract', function ($query) {
                $query->where('state', 'Active');
            })
            ->orderBy('name')
            ->selectRaw("UPPER(CONCAT(name, ' ', lastname)) as full_name")
            ->pluck('full_name')
            ->toArray();

        $external_employees = ExternalEmployee::whereHas('cost_line', function ($query) {
            $query->where('name', 'HUAWEI')
                ->orWhere('name', 'ADMINISTRATIVO');
        })
            ->orderBy('name')
            ->selectRaw("UPPER(CONCAT(name, ' ', lastname)) as full_name")
            ->pluck('full_name')
            ->toArray();

        $merged = array_merge($employees, $external_employees);
        $merged = array_unique($merged);
        sort($merged);

        if (!in_array('ADMINISTRATIVO', $merged)) {
            array_unshift($merged, 'ADMINISTRATIVO');
        } else {
            $merged = array_values(array_diff($merged, ['ADMINISTRATIVO']));
            array_unshift($merged, 'ADMINISTRATIVO');
        }

        return $merged;
    }


    public static function getVariableExpenseTypes(): array
    {
        return [
            "Adicional Camioneta",
            "Consumibles",
            "EPPS",
            "Fletes",
            "Herramientas",
            "Hospedaje",
            "Materiales",
            "Movilidad",
            "Transporte",
            "Otros",
        ];
    }

    public static function getStaticExpenseTypes(): array
    {
        return [
            "Alimentación",
            "Combustible",
            "Planilla",
        ];
    }

    public static function getCDPTypes(): array
    {
        return [
            "Efectivo",
            "Depósito",
            "Factura",
            "Boleta",
            "RH",
            "Yape",
            "Pendiente Factura",
        ];
    }
}
