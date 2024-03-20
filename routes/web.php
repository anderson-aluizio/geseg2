<?php

use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\CargoDocumentoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DocumentoFuncionarioController;
use App\Http\Controllers\ExportarXlsxController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ImagemViewController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/image-view/{path}', ImagemViewController::class)->where('path', '.*')->name('image-view');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('select-controller', [SelectController::class, 'handler'])->name('select-controller');

    Route::get('inicio', InicioController::class)->name('inicio');

    Route::resource('users', UserController::class)->only('index', 'create', 'store', 'edit', 'update');

    Route::controller(AccessControlController::class)->group(function () {
        Route::get('accesscontrol', 'index')->name('accesscontrol.index');
        Route::post('accesscontrol/role', 'storeRole')->name('accesscontrol.role.store');
        Route::put('accesscontrol/role/{role}', 'updateRole')->name('accesscontrol.role.update');
        Route::delete('accesscontrol/role/{role}', 'deleteRole')->name('accesscontrol.role.delete');
    });

    Route::get('exportar-xlsx', ExportarXlsxController::class)->name('exportar-xlsx');

    Route::resource('funcionarios', FuncionarioController::class)->only('index', 'show');

    Route::resource('documentos', DocumentoController::class)->only('index', 'update', 'store');

    Route::resource('cargos', CargoController::class)->only('index', 'edit');
    Route::put('cargo-documento/store/{cargo}', [CargoDocumentoController::class, 'store'])->name('cargo-documento.store');
    Route::delete('cargo-documento/delete/{cargo}', [CargoDocumentoController::class, 'delete'])->name('cargo-documento.delete');

    Route::get('documento-funcionarios/{funcionario}', [DocumentoFuncionarioController::class, 'index'])->name('documento-funcionarios.index');
    Route::resource('documento-funcionarios', DocumentoFuncionarioController::class)->only('update', 'store');
});
