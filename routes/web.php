<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'user'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'user'])
    ->name('profile');
    Route::get('/demo', [DemoController::class, 'index']);

// Route::view('demo', 'demo')->name('demo');

Route::get('/home', [HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
