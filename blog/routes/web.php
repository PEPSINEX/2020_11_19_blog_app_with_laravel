<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/react', function () {
    return view('react');
});
Auth::routes();

Route::get('/home', [Controllers\HomeController::class, 'index'])->name('home');

// Socialite導入のため
Route::get('login/facebook', [Controllers\Auth\LoginController::class, 'redirectToFacebookProvider'])->name('facebook');
Route::get('login/facebook/callback', [Controllers\Auth\LoginController::class, 'handleFacebookProviderCallback']);
Route::get('login/twitter', [Controllers\Auth\LoginController::class, 'redirectToTwitterProvider'])->name('twitter');
Route::get('login/twitter/callback', [Controllers\Auth\LoginController::class, 'handleTwitterProviderCallback']);
