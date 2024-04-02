<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;

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

Route::get('dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::view('dashboard', 'dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'user'])
    ->name('profile');
Route::get('/demo', [DemoController::class, 'index']);
Route::get('/favicon.png', function () {
    return response()->redirectTo(config('app.asset_url') . '/storage/favicon.png', 302, [
        'Content-Type' => 'image/png'
    ]);
});

// Route::view('demo', 'demo')->name('demo');
Route::view('public','PublicCompletedOrders')->name('public');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/invoices/{order}', [InvoiceController::class, 'print'])->name('invoice.print');


require __DIR__ . '/auth.php';