<?php

use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Statamic\Http\Controllers\CP\Auth\LoginController;

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

Route::get('/instagram-account', [InstagramController::class, 'get_posts']);

/* Public Routes */
Route::group([
//    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localize',
        'localeSessionRedirect', //redirect if there's a locale in session
        'localeViewPath']
], function () {
    Route::middleware('auth')->group(function () {
        Route::statamic('/search', 'search');
    });

    // Auth
    Route::statamic('/login', 'authentication.login')->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::post('/contact-us', [PublicController::class, 'contactForm']);
});

/* Authenticated Routes */
Route::group([
//    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localize',
        'localeSessionRedirect', //redirect if there's a locale in session
        'localeViewPath',
        'auth',]
], function () {
    Route::resource('/users', UserController::class);
});


