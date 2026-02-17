<?php

use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', function () {
    // Login logic will be implemented
});

Route::get('register', function () {
    return view('auth.register');
})->name('register');

Route::post('register', function () {
    // Registration logic will be implemented
});

Route::post('logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');