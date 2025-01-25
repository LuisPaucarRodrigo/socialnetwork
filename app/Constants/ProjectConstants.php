<?php

namespace App\Constants;

class ProjectConstants
{
    public static function __callStatic(string $name, array $arguments): string
    {
        $constant = strtoupper($name); // Convertir el nombre a mayúsculas
        if (defined("self::$constant")) {
            return constant("self::$constant");
        }
        throw new \Exception("La constante '$name' no existe.");
    }

    //expenses constants
    public const EXP_TYPE_ADDITIONAL = 'additional';
    public const EXP_TYPE_STATIC = 'static';
    public const EXP_TYPE_PAYROLL = 'payroll';
    public const EXP_TYPE_HMONTHLY = 'huawei_monthly';
    public const EXP_TYPE_PEXTEXP = 'pext_expense';


}
