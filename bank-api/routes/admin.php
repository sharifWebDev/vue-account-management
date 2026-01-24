<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/artisan/optimize', [AdminController::class, 'optimize'])->name('artisan.optimize');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/toggle-status/{id}', [AdminController::class, 'toggleStatus'])->name('data.toggleStatus');

    Route::prefix('/accounts')
        ->controller(\App\Http\Controllers\AccountController::class)
        ->group(function () {
            Route::get('/', 'index')->name('accounts.index');
            Route::get('/create', 'create')->name('accounts.create');
            Route::get('/{id}/edit', 'edit')->name('accounts.edit');
            Route::get('/{id}/show', 'show')->name('accounts.show');
        });
    Route::prefix('/companies')
        ->controller(\App\Http\Controllers\CompanyController::class)
        ->group(function () {
            Route::get('/', 'index')->name('companies.index');
            Route::get('/create', 'create')->name('companies.create');
            Route::get('/{id}/edit', 'edit')->name('companies.edit');
            Route::get('/{id}/show', 'show')->name('companies.show');
        });

    Route::prefix('/banks')
        ->controller(\App\Http\Controllers\BankController::class)
        ->group(function () {
            Route::get('/', 'index')->name('banks.index');
            Route::get('/create', 'create')->name('banks.create');
            Route::get('/{id}/edit', 'edit')->name('banks.edit');
            Route::get('/{id}/show', 'show')->name('banks.show');
        });

    Route::prefix('/branches')
        ->controller(\App\Http\Controllers\BranchController::class)
        ->group(function () {
            Route::get('/', 'index')->name('branches.index');
            Route::get('/create', 'create')->name('branches.create');
            Route::get('/{id}/edit', 'edit')->name('branches.edit');
            Route::get('/{id}/show', 'show')->name('branches.show');
        });
    Route::prefix('/accounts')
        ->controller(\App\Http\Controllers\AccountController::class)
        ->group(function () {
            Route::get('/', 'index')->name('accounts.index');
            Route::get('/create', 'create')->name('accounts.create');
            Route::get('/{id}/edit', 'edit')->name('accounts.edit');
            Route::get('/{id}/show', 'show')->name('accounts.show');
        });

    Route::prefix('/transactions')
        ->controller(\App\Http\Controllers\TransactionController::class)
        ->group(function () {
            Route::get('/', 'index')->name('transactions.index');
            Route::get('/create', 'create')->name('transactions.create');
            Route::get('/{id}/edit', 'edit')->name('transactions.edit');
            Route::get('/{id}/show', 'show')->name('transactions.show');
        });
    Route::prefix('/users')
        ->controller(\App\Http\Controllers\UserController::class)
        ->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::get('/create', 'create')->name('users.create');
            Route::get('/{id}/edit', 'edit')->name('users.edit');
            Route::get('/{id}/show', 'show')->name('users.show');
        });
});
