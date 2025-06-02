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
    public const AFP = 'AFP';
    public const ONP = 'ONP';
    public const SALARIO_BASICO = 'Salario básico';


    // doc types
    public const BOLETA_DE_PAGO = "Boleta de Pago";
    public const FACTURA = "Factura";
    public const RECIBO = "Recibo";
    public const RH = "Recibo por Honorarios";
    public const COMPROBANTE_DE_PAGO = "Comprobante de Pago";
    public const VOUCHER_DE_PAGO = "Voucher de Pago";
    public const SIN_COMPROBANTE = "Sin Comprobante";

    
    public static function payrollExpenseTypes(): array
    {
        return [
            self::SCTR_PENSIONARIO,
            self::SCTR_SALUD,
            self::AFP,
            self::ONP,
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

    

    public static function countPayrollExpenseTypes() : int {return count(self::payrollExpenseTypes());}
    public static function countPayrollDocTypes() : int {return count(self::payrollDocTypes());}


}