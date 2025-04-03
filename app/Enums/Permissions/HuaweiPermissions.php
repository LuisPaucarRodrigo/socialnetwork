<?php

namespace App\Enums\Permissions;

enum HuaweiPermissions: string
{
    

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
