<?php

use Illuminate\Support\Facades\Route;

// Semua route akan mengarah ke Vue.js SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');