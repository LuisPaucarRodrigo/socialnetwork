<?php

use App\Http\Controllers\DocumentManagement\FolderController;
use App\Http\Controllers\DocumentGestion\ArchivesController;
use App\Http\Controllers\DocumentManagement\LocalDriveController;
use Illuminate\Support\Facades\Route;




Route::get('/documment_management/{folder_id?}', [FolderController::class, 'folder_index'])->name('documment.management.folders');
Route::post('/documment_management/store', [FolderController::class, 'folder_store'])->name('documment.management.folders.store');
Route::get('/test_folder_download/{folder_id}', [FolderController::class, 'folder_download'])->name('folder.test.download');

Route::get('/documentGestion/{folder}/archives', [ArchivesController::class, 'show'])->name('archives.show');
Route::post('/documentGestion/{folder}/archives/post', [ArchivesController::class, 'create'])->name('archives.post');
Route::get('/documentGestion/{folder}/archives/{archive}/download', [ArchivesController::class, 'downloadArchive'])->name('archives.download');
Route::delete('/documentGestion/{folder}/archives/{archive}/destroy', [ArchivesController::class, 'destroy'])->name('archives.destroy');
Route::post('/documentGestion/{folder}/archives/{archive}/assignUsers', [ArchivesController::class, 'assignUsers'])->name('archives.assign.users');
Route::get('/documentGestion/{folder}/archives/{archive}/observations', [ArchivesController::class, 'observationsPerArchive'])->name('archives.observations');
Route::post('/documentGestion/archives/{archive}/observate', [ArchivesController::class, 'saveObservation'])->name('archives.observations.save');
Route::post('/documentGestion/archives/{archive}/upgrade', [ArchivesController::class, 'upgradeArchive'])->name('archives.upgrade');

Route::get('/documentGestion/alarms', [ArchivesController::class, 'getAlarmPerUser'])->name('archives.alarms');
Route::get('/documentGestion/archives/{archive}/pdf', [ArchivesController::class, 'getPDF'])->name('archives.get.pdf');



Route::get('/serach_folder_test/{folder_id}', [FolderController::class, 'search_in_folder'])->name('search.folder.test');



//Local Drive routes
Route::get('/local-drive-gestion', [LocalDriveController::class, 'localDriveIndex'])->name('local.drive.index');
Route::post('/local-drive-gestion/store/{path?}', [LocalDriveController::class, 'localDriveStore'])->name('local.drive.store');
Route::post('/local-drive-gestion/folder_archive_delete', [LocalDriveController::class, 'localDriveDelete'])->name('local.drive.delete');
Route::get('/local-drive-gestion/folder_archive_dowload', [LocalDriveController::class, 'localDriveDownload'])->name('local.drive.download');