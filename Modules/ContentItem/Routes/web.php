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
    Route::get('citem', 'ContentItemController@index')->name('citem');
    Route::group(['prefix' => 'citem', 'as'=>'citem.'], function () {
        Route::post('datatable-data', 'ContentItemController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ContentItemController@store_or_update_data')->name('store.or.update');
        Route::post('show', 'ContentItemController@show')->name('show');
        Route::post('edit', 'ContentItemController@edit')->name('edit');
        Route::post('delete', 'ContentItemController@delete')->name('delete');
        Route::post('bulk-delete', 'ContentItemController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ContentItemController@change_status')->name('change.status');
    });
});
