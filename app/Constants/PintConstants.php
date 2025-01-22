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

    // costs pint types
    public const HOSPEDAJE = 'Hospedaje';
    public const ENCOMIENDA = 'Encomienda';
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

    public const ALQUILER_DE_VEHICULOS = 'Alquiler de Vehículos';
    public const ALQUILER_DE_LOCALES = 'Alquiler de Locales';
    public const COMBUSTIBLE_UM = 'Combustible UM';
    public const COMBUSTIBLE_GEP = 'Combustible GEP';
    public const CELULARES = 'Celulares';
    public const PROVEIDOS = 'Proveídos';
    public const TERCEROS = 'Terceros';
    public const VIATICOS = 'Viáticos';
    public const REPOSICION_DE_EQUIPO = 'Reposición de Equipo';
    public const ADICIONALES = 'Adicionales';
    public const FILTROS_Y_ACEITES = 'Filtros y Aceites';
    public const PLANILLA = 'Planilla';




    //ac doc types
    public const SIN_COMPROBANTE = "Sin Comprobante";
    public const RH = "Recibo por Honorarios";
    public const FACTURA = "Factura";
    public const BOLETA = "Boleta";
    public const VOUCHER_DE_PAGO = "Voucher de Pago";

    //ac state types
    public const PENDIENTE = "Pendiente";
    public const ACEPTADO = "Aceptado";    
    public const ACEPTADO_VALIDADO = "Aceptado - Validado";    
    public const RECHAZADO = "Rechazado";

    //zones
    public const AREQUIPA = "Arequipa";
    public const CHALA = "Chala";
    public const MOQUEGUA = "Moquegua";
    public const TACNA = "Tacna";
    public const MDD1 = "MDD1";
    public const MDD2 = "MDD2";
    public const MDD1_PM = "MDD1-PM";
    public const MDD2_MAZ = "MDD2-MAZ";
    public const OFICINA = "Oficina";


    public static function acExpenseTypes(): array
    {
        return [
            self::HOSPEDAJE,
            self::ENCOMIENDA,
            self::CONSUMIBLES,
            self::PASAJE_INTERPROVINCIAL,
            self::TAXIS_Y_PASAJES,
            self::BANDEOS,
            self::PEAJE,
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
            self::SIN_COMPROBANTE,
            self::FACTURA,
            self::BOLETA,
            self::VOUCHER_DE_PAGO,
            self::RH,
        ];
    }

    public static function acStateTypes (): array{
        return [
            self::PENDIENTE,
            self::ACEPTADO,
            self::RECHAZADO,  
            self::ACEPTADO_VALIDADO,  
        ];
    }

    public static function acStatesPenAccep (): array{
        return [
            self::PENDIENTE,
            self::ACEPTADO,
            self::ACEPTADO_VALIDADO,
        ];
    }

    public static function acZones (): array {
        return [
            self::AREQUIPA,
            self::CHALA,
            self::MOQUEGUA,
            self::TACNA,
            self::MDD1_PM,
            self::MDD2_MAZ,
            self::OFICINA,
        ];
    }

    public static function scExpenseTypes() : array {
        return [
            self::ALQUILER_DE_VEHICULOS,
            self::ALQUILER_DE_LOCALES,
            self::COMBUSTIBLE_UM,
            self::COMBUSTIBLE_GEP,
            self::CELULARES,
            self::PROVEIDOS,
            self::TERCEROS,
            self::VIATICOS,
            self::SEGUROS_Y_POLIZAS,
            self::GASTOS_DE_REPRESENTACION,
            self::REPOSICION_DE_EQUIPO,
            self::HERRAMIENTAS,
            self::EQUIPOS,
            self::EPPS,
            self::ADICIONALES,
            self::FILTROS_Y_ACEITES,
            self::DANOS_DE_VEHICULOS,
            self::PLANILLA,
            self::OTROS,
        ];
    }

    public static function scDocTypes(): array
    {
        return [
            self::SIN_COMPROBANTE,
            self::FACTURA,
            self::BOLETA,
            self::VOUCHER_DE_PAGO,
            self::RH,
        ];
    }


    public static function scZones () : array {
        return [
            self::AREQUIPA,
            self::CHALA,
            self::MOQUEGUA,
            self::TACNA,
            self::MDD1_PM,
            self::MDD2_MAZ,
            self::OFICINA,
        ];
    }

    public static function scStatesTypes (): array{
        return [
            self::PENDIENTE,
            self::ACEPTADO_VALIDADO,
        ];
    }

    public static function scExpensesThatDontCount (): array { 
        return [
        PintConstants::COMBUSTIBLE_GEP,
        PintConstants::REPOSICION_DE_EQUIPO,
    ];}


    public static function mobileExpenses(): array
    {
        return [
            self::COMBUSTIBLE_UM,
            self::COMBUSTIBLE_GEP,
            self::HOSPEDAJE,
            self::PASAJE_INTERPROVINCIAL,
            self::PEAJE,
            self::TAXIS_Y_PASAJES,
            self::COCHERAS,
            self::APOYOS,
            self::ACARREOS,
            self::ENCOMIENDA,
            self::CONSUMIBLES,
            self::BANDEOS,
            self::BANDEOS,
            self::OTROS,
        ];
    }

    public static function mobileDocTypes(): array
    {
        return [
            self::SIN_COMPROBANTE,
            self::FACTURA,
            self::BOLETA,
            self::VOUCHER_DE_PAGO,
            self::RH,
        ];
    }

    public static function mobileZones () : array {
        return [
            self::AREQUIPA,
            self::CHALA,
            self::MOQUEGUA,
            self::TACNA,
            self::MDD1_PM,
            self::MDD2_MAZ,
        ];
    }


    public static function countAcExpenseTypes(): int{return count(self::acExpenseTypes());}
    public static function countAcDocTypes(): int{return count(self::acDocTypes());}
    public static function countAcZones(): int{return count(self::acZones());}
    public static function countAcStateTypes(): int{return count(self::acStateTypes());}

    public static function countScExpenseTypes() : int {return count(self::scExpenseTypes());}
    public static function countScDocTypes(): int{return count(self::scDocTypes());}
    public static function countScZones() : int {return count(self::scZones());}
    public static function countScStatesTypes() : int {return count(self::scStatesTypes());}

    
}

