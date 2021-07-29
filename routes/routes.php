<?php

use Illuminate\Support\Facades\Route;

use OZiTAG\Tager\Backend\Files\Enums\FilesScope;
use OZiTAG\Tager\Backend\Rbac\Facades\AccessControlMiddleware;
use OZiTAG\Tager\Backend\Files\Controllers\UserFilesController;

Route::group(['prefix' => 'admin/userfiles', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::group(['middleware' => [AccessControlMiddleware::scopes(FilesScope::FilesView)]], function () {
        Route::get('/', [UserFilesController::class, 'index']);
        Route::get('/count', [UserFilesController::class, 'count']);
        Route::post('/', [UserFilesController::class, 'store'])->middleware([
            AccessControlMiddleware::scopes(FilesScope::FilesCreate)
        ]);
        Route::delete('/{id}', [UserFilesController::class, 'delete'])->middleware([
            AccessControlMiddleware::scopes(FilesScope::FilesDelete)
        ]);
    });
});
