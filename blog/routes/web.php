<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

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

// ------------------------------------------------------------------------
// ここからビジネスロジック
function rightHeaderLayout()
{
    $request = request()->path();

    if(Auth::check()){ return 'layouts/rightHeader/authenticated/userMenu'; }
    if($request == 'login'){ return 'layouts/rightHeader/unAuthenticated/login'; }
    if($request == 'register'){ return 'layouts/rightHeader/unAuthenticated/register'; }
    return 'layouts/rightHeader/unAuthenticated/other';
}

View::composer('*', function($view)
{
    $rightHeaderLayout = rightHeaderLayout();
    $view->with('rightHeaderLayout', $rightHeaderLayout);
});
// ここまでビジネスロジック
// ------------------------------------------------------------------------

Route::get('/', function () {
    return view('welcome');
});

Route::get('/react', function () {
    return view('react');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
