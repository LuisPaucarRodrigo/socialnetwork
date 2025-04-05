<?php

namespace App\Policies\HumanResources;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Enums\Permissions\HumanResourcesPermissions;

class DocumentSpreedSheetPolicy
{
    /**
     * Create a new policy instance.
     */
    public function sections(): array
    {
        $user = Auth::user();

        $permissionMap = [
            HumanResourcesPermissions::ds_section_additionaldocs->value => 2,
            HumanResourcesPermissions::ds_section_entrydocs->value => 3,
            // etc...
        ];
        $sections = [1,4,5,6,7,8,9,10];
        foreach ($permissionMap as $permission => $sectionId) {
            if ($user->hasPermission($permission)) { $sections[] = $sectionId;}
        }

        return $sections;
    }
}
