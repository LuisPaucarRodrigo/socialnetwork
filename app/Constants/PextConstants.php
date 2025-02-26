<?php

namespace App\Constants;

use Illuminate\Support\Collection;

class PextConstants
{
    public static function getZone(): array
    {
        return [
            "Arequipa",
            "Moquegua",
            "Tacna",
            "Cuzco",
            "Puno",
            "MDD",
            "Sandia"
        ];
    }

    public static function getExpenseType(): array
    {
        return [
            "Hospedaje",
            "Pasaje Interprovincial",
            "Peaje",
            "Taxis y Pasajes",
            "Encomienda",
            "Combustible UM",
            "Bandeos",
            "Herramientas",
            "Equipos",
            "Epps",
            "Seguro y Pólizas",
            "Consumibles",
            "Otros"
        ];
    }

    public static function getExpenseTypeFixed(): array
    {
        // Juntamos los dos arrays
        return [
            'Alquiler de Vehículos',
            'Alquiler de Locales',
            'Combustible',
            'Celulares',
            'Terceros',
            'Viáticos',
            'Seguros y Pólizas',
            'Gastos de Representación',
            'Reposición de Equipo',
            'Herramientas',
            'Equipos',
            'EPPs',
            'Adicionales',
            'Daños de Vehículos',
            'Planilla',
            'Otros',
            'Adicionales',
            'Daños de Vehículos',
            'Planilla',
            "Pago a Terceros",
            'Otros'
        ];
    }

    public static function getDocumentsType(): array
    {
        return [
            "Sin Comprobante",
            "RH(Recibo por Honorarios)",
            "Factura",
            "Boleta",
            "Ticket",
            "Yape-Plin"
        ];
    }
}
