<?php

namespace App\Constants;

use App\Models\Employee;
use App\Models\ExternalEmployee;
use App\Models\HuaweiMonthlyExpense;

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
            "ADICIONAL",
            "ADICIONAL CAMIONETA",
            "ALIMENTACION",
            "COCHERA",
            "COMBUSTIBLE",
            "CONSUMIBLES",
            "ENCOMIENDA",
            "EPPS",
            "FLETES",
            "HERRAMIENTAS",
            "HOSPEDAJE",
            "MATERIALES",
            "MOVILIDAD",
            "PEAJE",
            "TRANSPORTE",
            "OTROS"
        ];

    }

    public static function getStaticExpenseTypes(): array
    {
        return [
            "PLANILLA",
        ];
    }

    public static function getCDPTypes(): array
    {
        return [
            "EFECTIVO",
            "DEPOSITO",
            "FACTURA",
            "BOLETA",
            "RH",
            "YAPE",
            "PENDIENTE FACTURA",
            "PLIN",
            "TRANSFERENCIA"
        ];
    }

    public static function getMacroProjects(): array
    {
        return [
            "IP 2024",
            "FTTH 2024",
            "DWDM 2024",
            "IP 2025",
            "FTTH 2025",
            "DWDM 2025",
        ];
    }

    public static function getOperators(): array
    {
        return [
            "Claro",
            "Entel",
            'Telefonica',
        ];
    }

    public static function getZones(): array
    {
        return HuaweiMonthlyExpense::pluck('zone')
        ->filter()
        ->unique()
        ->values()
        ->toArray();
    }
    //commit
}
