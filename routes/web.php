<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->guest('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('get_by_domain', [App\Http\Controllers\HomeController::class, 'get_by_domain']);
Route::get('get_breach_info', [App\Http\Controllers\HomeController::class, 'get_breach_info']);
Route::get('sort_domain', [App\Http\Controllers\HomeController::class, 'sort_domain']);
Route::get('sort_email', [App\Http\Controllers\HomeController::class, 'sort_email']);
Route::get('/search_by_keyword', [App\Http\Controllers\HomeController::class, 'search_by_keyword']);
Route::get('/search_by_email', [App\Http\Controllers\HomeController::class, 'search_by_email']);
Route::get('/get_emails_by_pagination', [App\Http\Controllers\HomeController::class, 'get_emails_by_pagination']);

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Auth::routes();

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
Auth::routes();

Route::get('/searchemail', [App\Http\Controllers\SearchController::class, 'search_email']);
// Route::post('/searchemail', [App\Http\Controllers\SearchController::class, 'search_email']);

Route::get('/searchdomain', [App\Http\Controllers\SearchController::class, 'search_domain']);

Route::get('/search', 'App\Http\Controllers\SearchController@index')->name('search');

Route::get('/account', 'App\Http\Controllers\AccountController@index')->name('account');

Route::get('/support', 'App\Http\Controllers\SupportController@index')->name('support');

Route::get('/policy', 'App\Http\Controllers\PolicyController@index')->name('policy');

Route::get('/gift', 'App\Http\Controllers\GiftController@index')->name('gift');

Route::post('/upload_policy', [App\Http\Controllers\PolicyController::class, 'upload_policy']);
Route::post('/edit_policy', [App\Http\Controllers\PolicyController::class, 'edit_policy']);
Route::post('/delete_policy', [App\Http\Controllers\PolicyController::class, 'delete_policy']);
Route::post('/add_faq', [App\Http\Controllers\PolicyController::class, 'add_faq']);
Route::post('/edit_faq', [App\Http\Controllers\PolicyController::class, 'edit_faq']);
Route::post('/delete_faq', [App\Http\Controllers\PolicyController::class, 'delete_faq']);

Route::post('/save_notification_email', [App\Http\Controllers\AccountController::class, 'save_notification_email']);
Route::post('/save_notification_status', [App\Http\Controllers\AccountController::class, 'save_notification_status']);

Route::get('/user/edit', [App\Http\Controllers\UserController::class, 'edit']);
Route::get('user/edit/{key}', [App\Http\Controllers\UserController::class, 'edit']);
Route::put('/user/edit', [App\Http\Controllers\UserController::class, 'edit']);
Route::get('user/delete/{key}', [App\Http\Controllers\UserController::class, 'delete']);

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	Route::get('map', function () {return view('pages.maps');})->name('map');
	Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});