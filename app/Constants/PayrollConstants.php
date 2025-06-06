<?php

namespace App\Constants;

class PayrollConstants
{
    public static function __callStatic(string $name, array $arguments): string
    {
        $constant = strtoupper($name); // Convertir el nombre a mayúsculas
        if (defined("self::$constant")) {
            return constant("self::$constant");
        }
        throw new \Exception("La constante '$name' no existe.");
    }

    // expenses payroll types
    public const SCTR_PENSIONARIO = 'SCTR pensionario';
    public const SCTR_SALUD = 'SCTR salud';
    public const AFP_EXPENSE = 'AFP';
    public const ONP_EXPENSE = 'ONP';
    public const SALARIO_BASICO = 'Salario básico';


    // doc types
    public const BOLETA_DE_PAGO = "Boleta de Pago";
    public const FACTURA = "Factura";
    public const RECIBO = "Recibo";
    public const RH = "Recibo por Honorarios";
    public const COMPROBANTE_DE_PAGO = "Comprobante de Pago";
    public const VOUCHER_DE_PAGO = "Voucher de Pago";
    public const SIN_COMPROBANTE = "Sin Comprobante";


    //pension types
    public const HABITAT = 'Habitat';
    public const INTEGRA = 'Integra';
    public const PRIMA = 'Prima';
    public const PROFUTURO = 'Profuturo';
    public const HABITADMX = 'HabitadMX';
    public const INTEGRAMX = 'IntegraMX';
    public const PRIMAMX = 'PrimaMX';
    public const PROFUTUROMX = 'ProfuturoMX';
    public const ONP = 'ONP';

    
    public static function payrollExpenseTypes(): array
    {
        return [
            self::SCTR_PENSIONARIO,
            self::SCTR_SALUD,
            self::AFP_EXPENSE,
            self::ONP_EXPENSE,
            self::SALARIO_BASICO,
        ];
    }


    public static function payrollDocTypes(): array
    {
        return [
            self::BOLETA_DE_PAGO,
            self::FACTURA,
            self::RECIBO,
            self::RH,
            self::COMPROBANTE_DE_PAGO,
            self::VOUCHER_DE_PAGO,
            self::SIN_COMPROBANTE,
        ];
    }

    public static function payrollPensionTypes(): array {
        return [
            self::HABITAT,
            self::INTEGRA,
            self::PRIMA,
            self::PROFUTURO,
            self::HABITADMX,
            self::INTEGRAMX,
            self::PRIMAMX,
            self::PROFUTUROMX,
            self::ONP,
        ];
    }

    

    public static function countPayrollExpenseTypes() : int {return count(self::payrollExpenseTypes());}
    public static function countPayrollDocTypes() : int {return count(self::payrollDocTypes());}
    public static function countPayrollPensionTypes() : int {return count(self::payrollPensionTypes());}


}