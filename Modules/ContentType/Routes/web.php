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
    Route::get('ctype', 'ContentTypeController@index')->name('ctype');
    Route::group(['prefix' => 'ctype', 'as'=>'ctype.'], function () {
        Route::post('datatable-data', 'ContentTypeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ContentTypeController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ContentTypeController@edit')->name('edit');
        Route::post('delete', 'ContentTypeController@delete')->name('delete');
        Route::post('bulk-delete', 'ContentTypeController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ContentTypeController@change_status')->name('change.status');
    });
});
