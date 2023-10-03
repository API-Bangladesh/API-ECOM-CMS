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
    Route::get('document', 'DocumentModuleController@index')->name('document');
    Route::group(['prefix' => 'document', 'as'=>'document.'], function () {
        Route::post('datatable-data', 'DocumentModuleController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'DocumentModuleController@store_or_update_data')->name('store.or.update');
        Route::post('show', 'DocumentModuleController@show')->name('show');
        Route::post('edit', 'DocumentModuleController@edit')->name('edit');
        Route::post('delete', 'DocumentModuleController@delete')->name('delete');
        Route::post('bulk-delete', 'DocumentModuleController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'DocumentModuleController@change_status')->name('change.status');
    });
});
