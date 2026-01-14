<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

use App\Livewire\Onboarding\Onboarding;
use App\Http\Controllers\AuthController;
use App\Livewire\Admin\Languages\Overview as LanguagesOverview;
use App\Livewire\Admin\Languages\Edit as EditLanguage;
use App\Livewire\Admin\Languages\NewLanguage;
use App\Livewire\Admin\RolesPerms\NewRole;
use App\Livewire\Admin\RolesPerms\EditRole;
use App\Livewire\Admin\RolesPerms\NewPermission;
use App\Livewire\Admin\RolesPerms\EditPermission;
use App\Livewire\Admin\RolesPerms\Overview as RolesPermsOverview;
use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Users\Overview as UserOverview;

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
    // ? Permissions
    Route::middleware('permission:manage_permissions')->group(function() {
        Route::get('/permissions', RolesPermsOverview::class)->name('admin.roles-perms.render');
        Route::get('/roles/new', NewRole::class)->name('admin.roles-perms.role.new');
        Route::get('/roles/edit/{uuid}', EditRole::class)->name('admin.roles-perms.role.edit');
        Route::get('/permissions/new', NewPermission::class)->name('admin.roles-perms.permission.new');
        Route::get('/permissions/edit/{uuid}', EditPermission::class)->name('admin.roles-perms.permission.edit');
    });

    // ? Languages
    Route::get('/languages', LanguagesOverview::class)->name('admin.languages.render');
    Route::get('/languages/edit/{id}', EditLanguage::class)->name('admin.languages.edit');
    Route::get('/languages/new', NewLanguage::class)->name('admin.languages.new');
  
    // ? Users
    Route::get('/users', UserOverview::class)->name('admin.users.render'); 
    Route::get('/users/edit/{uuid}', EditUser::class)->name('admin.users.edit'); 
});