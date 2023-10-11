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
    Route::get('page', 'PageController@index')->name('page');
    Route::group(['prefix' => 'page', 'as'=>'page.'], function () {
        Route::post('datatable-data', 'PageController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'PageController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'PageController@edit')->name('edit');
        Route::post('delete', 'PageController@delete')->name('delete');
        Route::post('bulk-delete', 'PageController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'PageController@change_status')->name('change.status');
    });
});
