<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Livewire\Dashboard;
use App\Livewire\Onboarding\Onboarding;
use App\Livewire\Admin\Companies\AddEmployee;
use App\Livewire\Admin\Companies\EditCompany;
use App\Livewire\Admin\Companies\NewCompany;
use App\Livewire\Admin\Companies\Overview as CompaniesOverview;
use App\Livewire\Admin\Dashboard\Dashboard as AdminDashboard;
use App\Livewire\Admin\RolesPerms\EditPermission;
use App\Livewire\Admin\RolesPerms\EditRole;
use App\Livewire\Admin\RolesPerms\NewPermission;
use App\Livewire\Admin\RolesPerms\NewRole;
use App\Livewire\Admin\RolesPerms\Overview as RolesPermsOverview;

// ! Guest Routes
Route::middleware('guest')->group(function() {
    // ? Login
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])
        ->middleware('throttle:5,1')
        ->name('login.post');

    // ? Register
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:6,1')
        ->name('register.post');
});

// ! Onboarding Routes
Route::middleware(['auth', 'redirect.onboarded'])->group(function() {
    // ? Onboarding
    Route::get('/onboarding', Onboarding::class)->name('onboarding.render');
});

// ! Authenticated Routes
Route::middleware(['auth', 'onboarding'])->group(function() {
    // ? Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ? Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard.render');
});

// ! Admin Routes
Route::middleware(['auth', 'onboarding'])->prefix('admin')->middleware('role:Superadmin')->group(function() {
    // ? Admin Dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard.render');

    // ? Companies
    Route::middleware('permission:manage_companies')->group(function() {
        Route::get('/companies', CompaniesOverview::class)->name('admin.companies.render');
        Route::get('/companies/new', NewCompany::class)->name('admin.companies.new');
        Route::get('/companies/edit/{id}', EditCompany::class)->name('admin.companies.edit');
        Route::get('/companies/edit/{id}/add-employee', AddEmployee::class)->name('admin.companies.add-employee');
    });

    // ? Permissions
    Route::middleware('permission:manage_permissions')->group(function() {
        Route::get('/permissions', RolesPermsOverview::class)->name('admin.roles-perms.render');
        Route::get('/roles/new', NewRole::class)->name('admin.roles-perms.role.new');
        Route::get('/roles/edit/{uuid}', EditRole::class)->name('admin.roles-perms.role.edit');
        Route::get('/permissions/new', NewPermission::class)->name('admin.roles-perms.permission.new');
        Route::get('/permissions/edit/{uuid}', EditPermission::class)->name('admin.roles-perms.permission.edit');
    });
});