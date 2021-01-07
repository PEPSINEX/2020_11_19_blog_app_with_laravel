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

Route::get('/login/{provider}', [Controllers\Auth\LoginController::class, 'redirectToProvider'])->where('provider', 'twitter|facebook');
Route::get('/login/{provider}/callback', [Controllers\Auth\LoginController::class, 'handleProviderCallback'])->where('provider', 'twitter|facebook');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/user', [Controllers\UserController::class, 'show'])->name('user');
    Route::post('/avatar', [Controllers\UserController::class, 'avatar'])->name('avatar');
    Route::delete('/avatar_delete', [Controllers\UserController::class, 'avatar_delete'])->name('avatar_delete');
});
