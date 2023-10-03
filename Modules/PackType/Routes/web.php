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
    Route::get('pack', 'PackTypeController@index')->name('pack');
    Route::group(['prefix' => 'pack', 'as'=>'pack.'], function () {
        Route::post('datatable-data', 'PackTypeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'PackTypeController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'PackTypeController@edit')->name('edit');
        Route::post('delete', 'PackTypeController@delete')->name('delete');
        Route::post('bulk-delete', 'PackTypeController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'PackTypeController@change_status')->name('change.status');
    });
});
