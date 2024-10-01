<?php

use App\Http\Controllers\Api\Accounts\AccountsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Clients\ClientsController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Revenue\RevenueController;
use App\Http\Controllers\Sales\SalesController;

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'api:BVaDN9hl'], function () {

        Route::prefix('auth')->group(function () {
            Route::prefix('email')->group(function () {
                Route::controller(AuthController::class)->group(function () {
                    Route::post('signup', 'registerwithemail');
                    Route::post('verify', 'verifyemail');
                    Route::post('verificationcode', 'subsequentemailverification');
                    Route::post('signin', 'loginwithemail');
                });
                Route::prefix('password')->group(function () {
                    Route::controller(AuthController::class)->group(function () {
                        Route::post('reset_request', 'passwordresetrequestviaemail');
                        Route::post('reset', 'passwordresetviaemail');
                    });
                });
            });
            Route::prefix('phone')->group(function () {
                Route::controller(AuthController::class)->group(function () {
                    Route::post('signup', 'registerwithphone');
                    Route::post('verify', 'verifyphone');
                    Route::post('verificationcode', 'subsequentphoneverification');
                    Route::post('signin', 'loginwithphone');
                });
                Route::prefix('password')->group(function () {
                    Route::controller(AuthController::class)->group(function () {
                        Route::post('reset_request', 'passwordresetrequestviaphone');
                        Route::post('reset', 'passwordresetviaphone');
                    });
                });
            });
        });
        Route::middleware('auth:sanctum')->group( function () {
            Route::prefix('sales')->group(function () {
                Route::controller(SalesController::class)->group(function () {
                    Route::post('create_account', 'createAccount');
                    Route::get('existing_clients','selectClients');
                });
            });
            Route::prefix('clients')->group(function () {
                Route::controller(ClientsController::class)->group(function () {
                    Route::post('create', 'create');
                    Route::get('view/{id}','view');
                    Route::post('update', 'update');
                    Route::delete('delete/{id}', 'delete');
                    Route::get('show/{id}', 'show');
                    Route::get('industries','industries');
                    Route::post('createindustry','createindustry');
                });
            });
            Route::prefix('accounts')->group(function () {
                Route::controller(AccountsController::class)->group(function () {
                    Route::post('create', 'create');
                    Route::get('view/{id}','view');
                    Route::get('client_types', 'clientTypes');
                    Route::get('client_type/{id}/{type}','accountWithStatus');
                    Route::get('account/{id}','show');
                    Route::get('client_projects/{id}/{client}','client');
                    Route::post('update', 'update');
                    Route::delete('delete/{id}', 'delete');
                    Route::post('updatestage', 'updateStage');
                    Route::get('stages', 'stages');
                });
            });
            Route::prefix('revenue')->group(function () {
                Route::controller(RevenueController::class)->group(function () {
                    Route::get('document_types', 'DocumentTypes');
                    Route::get('summary/{id}', 'Summary');
                });
            });
            Route::prefix('profile')->group(function () {
                Route::controller(ProfileController::class)->group(function () {
                    Route::post('update_password', 'UpdatePassword');
                    Route::post('update_profile', 'UpdateProfile');
                    Route::get('analytics/{id}', 'analytics');
                });
            });

            Route::post('logout',[AuthController::class,'logout'])->name('logouts');
        });
    });

    });
