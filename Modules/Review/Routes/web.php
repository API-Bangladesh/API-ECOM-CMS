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
    Route::get('review', 'ReviewController@index')->name('review');
    Route::group(['prefix' => 'review', 'as'=>'review.'], function () {
        Route::post('datatable-data', 'ReviewController@get_datatable_data')->name('datatable.data');
        Route::post('change-status', 'ReviewController@change_status')->name('change.status');
    });
});
