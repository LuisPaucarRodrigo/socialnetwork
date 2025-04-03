<?php

namespace App\Enums\Permissions;


enum DocumentsPermissions: string {

    case DOCUMENT_FOLDERS_VALIDATION = 'folder_validation';
    case DOCUMENT_FOLDERS_CHECK = 'folder_check';
    case DOCUMENT_FOLDERS_INVALIDATE = 'folder_invalidate';
    case DOCUMENT_FOLDERS_PERMISSION_SEE_DOWNLOAD = 'folder_permission_see_download';
    case DOCUMENT_FOLDERS_PERMISSION_CREATE = 'folder_permission_create';
    case DOCUMENT_FOLDERS_PERMISSION_ADD = 'folder_permission_add';
    case DOCUMENT_FOLDERS_PERMISSION_DELETE = 'folder_permission_delete';
    case DOCUMENT_FOLDERS_DELETE = 'folder_delete';
    case DOCUMENT_FOLDERS_PERMISSION_VIEW = 'folder_permission_view';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

}