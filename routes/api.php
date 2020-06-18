<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register','UserController@register');
Route::post('login', 'UserController@login')->name('login');
Route::post('verifyemail/{token}','UserController@verifyEmail');
Route::post('forgotpassword','PasswordResetController@create');
Route::post('forgotpassword/find','PasswordResetController@find');
Route::post('forgotpassword/reset','PasswordResetController@reset');
Route::post('sociallogin', 'UserController@socialLogin');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/logout', 'UserController@logout');
    Route::get('/getlistbooks', 'BooksController@getListBooks');
    Route::post('/addbook', 'BooksController@addBook');
    Route::post('/updatebook', 'BooksController@updatebook');
    Route::post('/deletebook', 'BooksController@deletebook');
});