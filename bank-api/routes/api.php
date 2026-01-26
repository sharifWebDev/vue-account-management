<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BankController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\TransactionController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('auth:sanctum')->get('/v1/user', function (Request $request) {
    return '$request->user()';
});

Route::controller(AuthController::class)
    ->prefix('/v1')
    ->group(function () {
        Route::post('auth/login', 'login');
        Route::post('auth/register', 'register');
        Route::post('auth/forget-password', 'forgetPassword');
        Route::post('auth/reset-password', 'resetPassword');
    });

Route::middleware('auth:sanctum', 'verified')
    ->prefix('/v1')
    ->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

        Route::controller(DashboardController::class)
            ->prefix('dashboard')
            ->group(function () {
                Route::get('/', 'dashboard');
                Route::get('/export', 'exportDashboardData');
                Route::get('/chart-data', 'getChartData');
            });

        Route::controller(AccountController::class)
            ->prefix('accounts')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');
            });

        Route::controller(CompanyController::class)
            ->prefix('companies')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');
            });

        Route::controller(BankController::class)
            ->prefix('banks')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');
            });

        Route::controller(BranchController::class)
            ->prefix('branches')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');
            });

        Route::controller(AccountController::class)
            ->prefix('accounts')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');
            });

        Route::controller(TransactionController::class)
            ->prefix('transactions')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');

                // New routes added for the full system
                Route::post('/soft-delete/{id}', 'softDelete');           // Soft delete (move to trash)
                Route::post('/undo-soft-delete/{id}', 'undoSoftDelete');  // Undo soft delete (restore from trash)
                Route::post('/change-status/{id}', 'changeStatus');       // Change transaction status (active/inactive)
                Route::get('/history', 'history');                        // Get transaction history with filters
                Route::get('/balance', 'getBalance');                     // Get account balance

                // Special transaction types
                Route::post('/deposit', 'deposit');                       // Make a deposit
                Route::post('/withdraw', 'withdraw');                     // Make a withdrawal
                Route::post('/bounce/{id}', 'markAsBounced');             // Mark transaction as bounced
            });

        Route::controller(UserController::class)
            ->prefix('users')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/all', 'getAll');
                Route::post('/', 'store');
                Route::get('/find/{id}', 'find');
                Route::put('/update/{id}', 'update');
                Route::delete('/destroy/{id}', 'destroy');
                Route::get('/trash-list', 'trash');
                Route::post('/restore/{id}', 'restore');
                Route::delete('/force-delete/{id}', 'forceDelete');
            });
    });
