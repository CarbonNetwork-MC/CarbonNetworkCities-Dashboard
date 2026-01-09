<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Livewire\Onboarding\Onboarding;

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

// ! Authenticated Routes
Route::middleware(['auth'])->group(function() {
    // ? Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ? Onboarding
    Route::get('/onboarding', Onboarding::class)->name('onboarding.render');
});
