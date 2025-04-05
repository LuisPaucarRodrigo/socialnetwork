<?php

namespace App\Enums\Permissions;

enum FinancePermissions: string
{
    

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
