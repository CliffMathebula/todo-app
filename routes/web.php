<?php

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

Route::group(['middleware' => ['auth']], function () {

  Route::get('/', 'TaskController@home');

  Route::group(['prefix' => 'task', ], function() {
    Route::post('add', 'TaskController@add');
    Route::post('edit', 'TaskController@edit');
    Route::post('move', 'TaskController@move');
    Route::post('delete', 'TaskController@delete');
  });

});

Auth::routes();
