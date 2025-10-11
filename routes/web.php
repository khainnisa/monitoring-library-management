<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');