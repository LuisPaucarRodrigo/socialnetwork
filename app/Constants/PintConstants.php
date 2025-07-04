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
    public const ACTIVOS = 'Activos';
    public const GASTOS_FINANCIEROS = 'Gastos Financieros';
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
    public const PRESTAMOS = 'Préstamos';
    public const IMPLEMENTACION_DE_OFICINA = 'Implementación de Oficina';
    public const RENOVACION_DE_OFICINA = 'Renovación de Oficina';

    //ac doc types
    public const SIN_COMPROBANTE = "Sin Comprobante";
    public const RH = "Recibo por Honorarios";
    public const FACTURA = "Factura";
    public const BOLETA = "Boleta";
    public const BOLETA_DE_VENTA = "Boleta de Venta";
    public const VOUCHER_DE_PAGO = "Voucher de Pago";
    public const BOLETA_DE_PAGO = "Boleta de Pago";
    public const COMPROBANTE_DE_PAGO = "Comprobante de Pago";
    public const RECIBO = "Recibo";

    //ac state types
    public const PENDIENTE = "Pendiente";
    public const ACEPTADO = "Aceptado";    
    public const ACEPTADO_VALIDADO = "Aceptado - Validado";    
    public const RECHAZADO = "Rechazado";
    public const NO_DISPONIBLE = "No Disponible";


    //zones
    public const AREQUIPA = "Arequipa";
    public const CHALA = "Chala";
    public const MOQUEGUA = "Moquegua";
    public const TACNA = "Tacna";
    public const MDD1_PM = "MDD1-PM";
    public const MDD2_MAZ = "MDD2-MAZ";
    public const SANDIA = "Sandia";
    public const MOQUEGUA_PEXT = "Moquegua-PEXT";
    public const SANDIA_PEXT = "Sandia-PEXT";
    public const MDD2_MAZ_PEXT = "MDD2-MAZ-PEXT";
    public const OFICINA = "Oficina";


    // ------- ADDITIONAL ----------------------

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
            self::COMBUSTIBLE_UM,
            self::COMBUSTIBLE_GEP,
            self::GASTOS_FINANCIEROS,
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
            self::SANDIA,
            self::MOQUEGUA_PEXT,
            self::SANDIA_PEXT,
            self::MDD2_MAZ_PEXT,
            self::OFICINA,
        ];
    }

    public static function acExpensesThatDontCount (): array { 
        return [
        PintConstants::COMBUSTIBLE_GEP,
        PintConstants::ACTIVOS,
        PintConstants::REPOSICION_DE_EQUIPO,
    ];}

    // ------------- STATIC -------------------------------

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
            self::ACTIVOS,
            self::PLANILLA,
            self::GASTOS_FINANCIEROS,
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
            self::SANDIA,
            self::MOQUEGUA_PEXT,
            self::SANDIA_PEXT,
            self::MDD2_MAZ_PEXT,
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
        PintConstants::ACTIVOS,
        PintConstants::REPOSICION_DE_EQUIPO,
    ];}


    
    // ---------- ADMINISTRATIVE -------------------------

    public static function admincostExpenseTypes(): array {
        return [
            self::PRESTAMOS,
            self::HOSPEDAJE,
            self::ENCOMIENDA,
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
            self::ACTIVOS,
            self::GASTOS_FINANCIEROS,
            self::ALQUILER_DE_VEHICULOS,
            self::ALQUILER_DE_LOCALES,
            self::COMBUSTIBLE_UM,
            self::COMBUSTIBLE_GEP,
            self::CELULARES,
            self::PROVEIDOS,
            self::TERCEROS,
            self::VIATICOS,
            self::REPOSICION_DE_EQUIPO,
            self::ADICIONALES,
            self::FILTROS_Y_ACEITES,
            self::PLANILLA,
            self::IMPLEMENTACION_DE_OFICINA, 
            self::RENOVACION_DE_OFICINA,
            self::OTROS,
        ];
    }

    public static function admincostDocTypes(): array
    {
        return [
            self::BOLETA_DE_VENTA,
            self::BOLETA_DE_PAGO,
            self::FACTURA,
            self::RECIBO,
            self::RH,
            self::SIN_COMPROBANTE,
            self::COMPROBANTE_DE_PAGO,
            self::VOUCHER_DE_PAGO,
        ];
    }


    



    // ---------- MOBILE ---------------------

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
            self::SANDIA,
            self::MOQUEGUA_PEXT,
            self::SANDIA_PEXT,
            self::MDD2_MAZ_PEXT,
        ];
    }


    public static function allZones (): array {
        return [
            self::AREQUIPA,
            self::CHALA,
            self::MOQUEGUA,
            self::TACNA,
            self::MDD1_PM,
            self::MDD2_MAZ,
            self::SANDIA,
            self::OFICINA,
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

    public static function countAdmincostExpenseTypes() : int {return count(self::admincostExpenseTypes());}
    public static function countAdmincostDocTypes() : int {return count(self::admincostDocTypes());}

    
}

