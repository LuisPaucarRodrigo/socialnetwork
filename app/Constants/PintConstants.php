<?php

namespace App\Constants;

class PintConstants
{
    public static function __callStatic(string $name, array $arguments): string
    {
        $constant = strtoupper($name); // Convertir el nombre a mayúsculas
        if (defined("self::$constant")) {
            return constant("self::$constant");
        }
        throw new \Exception("La constante '$name' no existe.");
    }


    // additional costs pint types
    public const HOSPEDAJE = 'Hospedaje';
    public const MENSAJERIA = 'Mensajería';
    public const CONSUMIBLES = 'Consumibles';
    public const PASAJE_INTERPROVINCIAL = 'Pasaje Interprovincial';
    public const TAXIS_Y_PASAJES = 'Taxis y Pasajes';
    public const BANDEOS = 'Bandeos';
    public const PEAJE = 'Peaje';
    public const HERRAMIENTAS = 'Herramientas';
    public const EQUIPOS = 'Equipos';
    public const DANOS_DE_VEHICULOS = 'Daños de Vehículos';
    public const EPPS = 'EPPs';
    public const SEGUROS_Y_POLIZAS = 'Seguros y Pólizas';
    public const GASTOS_DE_REPRESENTACION = 'Gastos de Representación';
    public const COCHERAS = 'Cocheras';
    public const APOYOS = 'Apoyos';
    public const ACARREOS = 'Acarreos';
    public const OTROS = 'Otros';


    //ac doc types
    public const EFECTIVO = "Efectivo";
    public const DEPOSITO = "Deposito";
    public const FACTURA = "Factura";
    public const BOLETA = "Boleta";
    public const VOUCHER_DE_PAGO = "Voucher de Pago";

    //ac state types
    public const PENDIENTE = "Pendiente";
    public const ACEPTADO = "Aceptado";    
    public const RECHAZADO = "Rechazado";


    //zones
    public const AREQUIPA = "Arequipa";
    public const CHALA = "Chala";
    public const MOQUEGUA = "Moquegua";
    public const TACNA = "Tacna";
    public const MDD1 = "MDD1";
    public const MDD2 = "MDD2";
    public const OFICINA = "Oficina";




    public static function acExpenseTypes(): array
    {
        return [
            self::HOSPEDAJE,
            self::MENSAJERIA,
            self::CONSUMIBLES,
            self::PASAJE_INTERPROVINCIAL,
            self::TAXIS_Y_PASAJES,
            self::BANDEOS,
            self::PEAJE,
            self::HERRAMIENTAS,
            self::EQUIPOS,
            self::DANOS_DE_VEHICULOS,
            self::EPPS,
            self::SEGUROS_Y_POLIZAS,
            self::GASTOS_DE_REPRESENTACION,
            self::COCHERAS,
            self::APOYOS,
            self::ACARREOS,
            self::OTROS
        ];
    }

    public static function acDocTypes(): array
    {
        return [
            self::EFECTIVO,
            self::DEPOSITO,
            self::FACTURA,
            self::BOLETA,
            self::VOUCHER_DE_PAGO,
        ];
    }

    public static function acStateTypes (): array{
        return [
            self::PENDIENTE,
            self::ACEPTADO,
            self::PENDIENTE,  
        ];
    }

    public static function acZones (): array {
        return [
            self::AREQUIPA,
            self::CHALA,
            self::MOQUEGUA,
            self::TACNA,
            self::MDD1,
            self::MDD2,
            self::OFICINA,
        ];
    }




    public static function countAcExpenseTypes(): int{return count(self::acExpenseTypes());}
    public static function countAcDocTypes(): int{return count(self::acDocTypes());}
    public static function countAcStateTypes(): int{return count(self::acStateTypes());}
    public static function countAcZones(): int{return count(self::acZones());}

    
}

