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

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});


Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');

    // Transactions
    Route::get('transactions', 'TransactionsController@index')->middleware('isAdmin');
    Route::post('transactions', 'TransactionsController@create')->middleware('isAdmin');
    Route::get('transactions/paymentTypes', 'TransactionsController@paymentTypes')->middleware('isAdmin');
    Route::get('transactions/statuses', 'TransactionsController@statuses')->middleware('isAdmin');
    Route::get('transactions/{transaction}', 'TransactionsController@transaction')->middleware('isAdmin');
    Route::patch('transactions/{transaction}', 'TransactionsController@update')->middleware('isAdmin');
});
