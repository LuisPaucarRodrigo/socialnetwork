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

    // costs pint types
    public const PEXT_MANTTO = 'PEXT_MANTTO';
    public const PEXT_ADDITIONAL = 'PEXT_ADDITIONAL';
    public const PINT_MANTTO = 'PINT_MANTTO';
    public const PINT_ADDITIONAL = 'PINT_ADDITIONAL';

    public static function projectTypes(): array
    {
        return [
            self::PEXT_MANTTO,
            self::PEXT_ADDITIONAL,
            self::PINT_MANTTO,
            self::PINT_ADDITIONAL,
           
        ];
    }

    public static function pintProjectTypes(): array {
        return [
            self::PINT_MANTTO => 'Pint Mantenimiento',
            self::PINT_ADDITIONAL => 'Pint Adicional',
        ];
    }
    public static function pextProjectTypes(): array {
        return [
            self::PEXT_MANTTO => 'Pext Mantenimiento',
            self::PEXT_ADDITIONAL => 'Pext Adicional',
        ];
    }
}
