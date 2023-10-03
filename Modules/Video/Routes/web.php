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

Route::group(['middleware' => ['auth']], function () {
    Route::get('video', 'VideoController@index')->name('video');
    Route::group(['prefix' => 'video', 'as'=>'video.'], function () {
        Route::post('datatable-data', 'VideoController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'VideoController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'VideoController@edit')->name('edit');
        Route::post('delete', 'VideoController@delete')->name('delete');
        Route::post('bulk-delete', 'VideoController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'VideoController@change_status')->name('change.status');
    });
});
