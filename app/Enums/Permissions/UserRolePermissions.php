<?php

namespace App\Enums\Permissions;

enum UserRolePermissions: string
{
    // USERS
    case USERS_INDEX = 'users_index';
    case USERS_SEARCH = 'users_search';
    case USERS_DETAILS = 'users_details';

    case USER_LINK_EMPLOYEE = 'link_employee';
    case USER_EDIT = 'user_edit';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    // ROLS
    case ROL_VIEW = 'rol_view';
    case ROL_CREATE = 'rol_create';
    case ROL_UPDATE = 'rol_update';
    case ROL_DELETE = 'rol_delete';
    case ROL_DETAILS = 'rol_details';

    

    
    //to do -> became policy

    //users'table

   
    

   

    

    


    


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
