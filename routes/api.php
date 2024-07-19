<?php

use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/files'], function () {
    Route::get('download/{uniqueLink}', [FileController::class, 'download'])->name('files.download');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('', [FileController::class, 'upload'])->name('files.upload');
        Route::get('user/current-space', [FileController::class, 'getCurrentSpace'])->name('files.get-current-space');
        Route::patch('{id}/rename', [FileController::class, 'rename'])->name('files.rename');
        Route::delete('{id}', [FileController::class, 'destroy'])->name('files.destroy');
        Route::get('{id}', [FileController::class, 'show'])->name('files.show');
        Route::patch('{id}/toggle-public', [FileController::class, 'togglePublic'])->name('files.toggle-public');
    });
});
Route::group(['prefix' => '/user'], function (){
    Route::post('register', [UserController::class, 'register'])->name('user.register');
    Route::post('login', [UserController::class, 'login'])->name('user.login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
    });
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'directories'], function () {
    Route::post('', [DirectoryController::class, 'store'])->name('directories.store');
    Route::patch('{id}/rename', [DirectoryController::class, 'rename'])->name('directories.rename');
    Route::delete('{id}', [DirectoryController::class, 'destroy'])->name('directories.destroy');
});

