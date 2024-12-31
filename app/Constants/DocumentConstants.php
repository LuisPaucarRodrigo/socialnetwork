<?php

namespace App\Constants;

class DocumentConstants
{
    public static function __callStatic(string $name, array $arguments): string
    {
        $constant = strtoupper($name); // Convertir el nombre a mayúsculas
        if (defined("self::$constant")) {
            return constant("self::$constant");
        }
        throw new \Exception("La constante '$name' no existe.");
    }


    public const SCTR = 'SCTR';
    public const POLIZA = 'Póliza';

    public static function grupalDouments () : array {
        return [
            self::SCTR,
            self::POLIZA
        ];
    }
}