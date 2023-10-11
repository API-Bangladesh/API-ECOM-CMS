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
    Route::get('media', 'MediaController@index')->name('page');
    Route::group(['prefix' => 'media', 'as'=>'media.'], function () {
        Route::post('datatable-data', 'MediaController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MediaController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'MediaController@edit')->name('edit');
        Route::post('delete', 'MediaController@delete')->name('delete');
        Route::post('bulk-delete', 'MediaController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'MediaController@change_status')->name('change.status');
    });
});
