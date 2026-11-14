<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Страница входа (только для неавторизованных)
Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'auth.login')->name('login');
});

// Выход из аккаунта
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Весь остальной сайт — строго после входа
Route::middleware('auth')->group(function () {
    Route::livewire('/', 'kanban.kanban-board')->name('home');
});
