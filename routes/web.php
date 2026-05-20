<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleHasPermissionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentTypesController;
use App\Http\Controllers\IndustriesController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\PresalesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\SolutionSubTypeController;
use App\Http\Controllers\SolutionTypesController;
use App\Http\Controllers\SPAccountsController;
use App\Http\Controllers\SPBusinessesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return Inertia::render('Auth/Login', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register')
        ]);
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::middleware(['check-active'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('industries', IndustriesController::class);
    Route::resource('solution-type', SolutionTypesController::class);
    Route::resource('solution', SolutionController::class);
    Route::resource('solution-sub-type', SolutionSubTypeController::class);
    Route::resource('document-type', DocumentTypesController::class);
    Route::resource('users', UsersController::class);
    Route::put('user/switch',[UsersController::class, 'switch']);
    Route::post('client/create_existing',[ClientsController::class, 'create_existing'])->name('create.existing.user');
    Route::put('client/update_existing',[ClientsController::class, 'update_existing'])->name('update.existing.user');

    Route::get('personnels', [UsersController::class, 'sales'])->name('personnels');
    Route::resource('rolepermissions', RoleHasPermissionsController::class);
    // Route::resource('clients', ClientsController::class);
    Route::get('clients/all', [ClientsController::class, 'index'])->name('clients/all');
    Route::get('clients/bs/{id}', [ClientsController::class, 'stg'])->name('clients.bs');
    Route::get('clients/existing', [ClientsController::class, 'existing'])->name('clients/existing');

    Route::get('clients/new', [ClientsController::class, 'new'])->name('clients/new');
    Route::get('clients/renewal', [ClientsController::class, 'renewal'])->name('clients/renewal');
    Route::get('clients/upselling', [ClientsController::class, 'upselling'])->name('clients/upselling');
    Route::get('accounts/client/{id}', [AccountsController::class, 'show'])->name('accounts.client');
    Route::get('accounts/account/{id}', [AccountsController::class, 'account'])->name('accounts.account');
    Route::get('accounts/user/{id}', [AccountsController::class, 'user'])->name('accounts.user');

    Route::get('sales_person/accounts', [SPAccountsController::class, 'index'])->name('sales_person.accounts');
    Route::post('sales_person/accounts/create', [SPAccountsController::class, 'create'])->name('sales_person.accounts.create');
    Route::post('sales_person/accounts/update', [SPAccountsController::class, 'update'])->name('sales_person.accounts.update');


    Route::get('sales_person/account/businesses/{id}', [SPBusinessesController::class, 'index']);
    Route::post('sales_person/accounts/businesses/create', [SPBusinessesController::class, 'create'])->name('sales_person.accounts.businesses.create');
    Route::post('sales_person/accounts/businesses/update', [SPBusinessesController::class, 'update'])->name('sales_person.accounts.businesses.update');
    Route::post('sales_person/accounts/businesses/update_stage', [SPBusinessesController::class, 'updateStage'])->name('sales_person.accounts.businesses.update_stage');
    Route::get('sales_person/accounts/account/{id}', [SPBusinessesController::class, 'account'])->name('sales_person.accounts.account');
    Route::get('sales_person/clients/bs/{id}', [SPBusinessesController::class, 'stg']);

    Route::get('crm/accounts', [CRMController::class, 'index'])->name('crm.accounts');
    Route::get('crm/renewals', [CRMController::class, 'renewals'])->name('crm.renewals');
    Route::post('crm/accounts/create', [CRMController::class, 'create'])->name('crm.accounts.create');
    Route::get('crm/account/businesses/{id}', [CRMController::class, 'business']);
    Route::post('crm/accounts/businesses/create', [CRMController::class, 'createbusiness'])->name('crm.accounts.businesses.create');

    Route::get('reports/accounts_sheet', [ReportsController::class, 'sheet'])->name('reports.accounts.sheet');
    Route::get('reports/custom', [ReportsController::class, 'customreportpage'])->name('reports.accounts.custom');
    Route::post('reports/create', [ReportsController::class, 'customreport'])->name('reports.custom.create');

    Route::get('presales/scoping', [PresalesController::class, 'scoping'])->name('presales.scoping');
    Route::post('presales/update_stage', [PresalesController::class, 'updateStage'])->name('presales.update_stage');
    Route::get('presales/tender/rfq', [PresalesController::class, 'tenderOrRFQ'])->name('presales.tender.rfq');
    Route::post('presales/accounts/create', [PresalesController::class, 'create'])->name('presales.accounts.create');

    Route::get('marketing', [MarketingController::class, 'index'])->name('marketing');

    Route::get('reports/mycustom', [ReportsController::class, 'mycustomreportpage'])->name('reports.accounts.mycustom');
    Route::post('reports/mycreate', [ReportsController::class, 'mycustomreport'])->name('reports.custom.mycreate');

});
// });
