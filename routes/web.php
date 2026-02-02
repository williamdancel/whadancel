<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/mgwm', function () {
    return Inertia::render('Proposals/Makagago/Home');
})->name('makagago.home');

Route::get('/', function () {
    return Inertia::render('Portfolio');
})->name('home');
