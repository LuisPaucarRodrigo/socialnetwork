<?php
namespace App\Support\RouteDefinitions;

use App\Http\Controllers\DocumentManagement\FolderController;
use App\Http\Controllers\DocumentGestion\ArchivesController;
use App\Http\Controllers\DocumentManagement\LocalDriveController;


class DocumentGestionRoutes
{
    public static function all(): array
    {
        return [
            [
                'uri' => 'document_management/folder_validation',
                'method' => 'get',
                'action' => [FolderController::class, 'folder_validation'],
                'permission' => false,
                'name' => 'documment.management.folders.validation',
            ],
            [
                'uri' => 'document_management/folder_validation/check/{folder_id}',
                'method' => 'post',
                'action' => [FolderController::class, 'folder_check'],
                'permission' => false,
                'name' => 'documment.management.folders.check',
            ],
            [
                'uri' => 'document_management/folder_validation/invalidate/{folder_id}',
                'method' => 'delete',
                'action' => [FolderController::class, 'folder_invalidate'],
                'permission' => false,
                'name' => 'documment.management.folders.invalidate',
            ],
            [
                'uri' => 'document_management/folder_permissions/{folder_area_id}',
                'method' => 'post',
                'action' => [FolderController::class, 'see_dowload_permission'],
                'permission' => false,
                'name' => 'documment.management.folders.permission.see_download',
            ],
            [
                'uri' => 'document_management/folder_permissions_create/{folder_area_id}',
                'method' => 'post',
                'action' => [FolderController::class, 'create_permission'],
                'permission' => false,
                'name' => 'documment.management.folders.permission.create',
            ],
            [
                'uri' => 'document_management/folder_permissions_add',
                'method' => 'post',
                'action' => [FolderController::class, 'folder_permission_add'],
                'permission' => false,
                'name' => 'documment.management.folders.permission.add',
            ],
            [
                'uri' => 'document_management/folder_permissions_delete/{folder_area_id}',
                'method' => 'delete',
                'action' => [FolderController::class, 'folder_permission_remove'],
                'permission' => false,
                'name' => 'documment.management.folders.permission.delete',
            ],
            [
                'uri' => 'document_management/folder_destroy/{folder_id}',
                'method' => 'delete',
                'action' => [FolderController::class, 'folder_delete'],
                'permission' => false,
                'name' => 'document.management.folder.destroy',
            ],
            [
                'uri' => 'documment_management/folder_permission/{folder_id}',
                'method' => 'get',
                'action' => [FolderController::class, 'folder_permissions'],
                'permission' => false,
                'name' => 'documment.management.folders.permissions',
            ],
            [
                'uri' => 'documment_management/{folder_id?}',
                'method' => 'get',
                'action' => [FolderController::class, 'folder_index'],
                'permission' => false,
                'name' => 'documment.management.folders',
            ],
            [
                'uri' => 'documment_management/store',
                'method' => 'post',
                'action' => [FolderController::class, 'folder_store'],
                'permission' => false,
                'name' => 'documment.management.folders.store',
            ],
            [
                'uri' => 'test_folder_download/{folder_id}',
                'method' => 'get',
                'action' => [FolderController::class, 'folder_download'],
                'permission' => false,
                'name' => 'folder.test.download',
            ],
            [
                'uri' => 'documentGestion/{folder}/archives',
                'method' => 'get',
                'action' => [ArchivesController::class, 'show'],
                'permission' => false,
                'name' => 'archives.show',
            ],
            [
                'uri' => 'documentGestion/{folder}/archives/post',
                'method' => 'post',
                'action' => [ArchivesController::class, 'create'],
                'permission' => false,
                'name' => 'archives.post',
            ],
            [
                'uri' => 'documentGestion/{folder}/archives/{archive}/download',
                'method' => 'get',
                'action' => [ArchivesController::class, 'downloadArchive'],
                'permission' => false,
                'name' => 'archives.download',
            ],
            [
                'uri' => 'documentGestion/{folder}/archives/{archive}/destroy',
                'method' => 'delete',
                'action' => [ArchivesController::class, 'destroy'],
                'permission' => false,
                'name' => 'archives.destroy',
            ],
            [
                'uri' => 'documentGestion/{folder}/archives/{archive}/assignUsers',
                'method' => 'post',
                'action' => [ArchivesController::class, 'assignUsers'],
                'permission' => false,
                'name' => 'archives.assign.users',
            ],
            [
                'uri' => 'documentGestion/{folder}/archives/{archive}/observations',
                'method' => 'get',
                'action' => [ArchivesController::class, 'observationsPerArchive'],
                'permission' => false,
                'name' => 'archives.observations',
            ],
            [
                'uri' => 'documentGestion/archives/{archive}/observate',
                'method' => 'post',
                'action' => [ArchivesController::class, 'saveObservation'],
                'permission' => false,
                'name' => 'archives.observations.save',
            ],
            [
                'uri' => 'documentGestion/archives/{archive}/upgrade',
                'method' => 'post',
                'action' => [ArchivesController::class, 'upgradeArchive'],
                'permission' => false,
                'name' => 'archives.upgrade',
            ],
            [
                'uri' => 'documentGestion/archives/{archive}/pdf',
                'method' => 'get',
                'action' => [ArchivesController::class, 'getPDF'],
                'permission' => false,
                'name' => 'archives.get.pdf',
            ],
            [
                'uri' => 'serach_folder_test/{folder_id}',
                'method' => 'get',
                'action' => [FolderController::class, 'search_in_folder'],
                'permission' => false,
                'name' => 'search.folder.test',
            ],
            [
                'uri' => 'local-drive-gestion',
                'method' => 'get',
                'action' => [LocalDriveController::class, 'localDriveIndex'],
                'permission' => false,
                'name' => 'local.drive.index',
            ],
            [
                'uri' => 'local-drive-gestion/store/{path?}',
                'method' => 'post',
                'action' => [LocalDriveController::class, 'localDriveStore'],
                'permission' => false,
                'name' => 'local.drive.store',
            ],
            [
                'uri' => 'local-drive-gestion/folder_archive_delete',
                'method' => 'post',
                'action' => [LocalDriveController::class, 'localDriveDelete'],
                'permission' => false,
                'name' => 'local.drive.delete',
            ],
            [
                'uri' => 'local-drive-gestion/folder_archive_dowload',
                'method' => 'get',
                'action' => [LocalDriveController::class, 'localDriveDownload'],
                'permission' => false,
                'name' => 'local.drive.download',
            ],
        ];
    }
}