<?php

namespace App\Constants;

use App\Models\Employee;

class HuaweiConstants
{
    public static function getEmployees(): array
    {
        $employees = Employee::whereHas('contract.cost_line', function ($query) {
            $query->where('name', 'HUAWEI');
        })
            ->orderBy('name')
            ->selectRaw("UPPER(CONCAT(name, ' ', lastname)) as full_name")
            ->pluck('full_name')
            ->toArray();
        array_unshift($employees, 'ADMINISTRATIVO');
        return $employees;
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