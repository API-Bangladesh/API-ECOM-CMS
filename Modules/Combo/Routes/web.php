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
    Route::get('combo', 'ComboController@index')->name('combo');
    Route::group(['prefix' => 'combo', 'as'=>'combo.'], function () {
        Route::post('datatable-data', 'ComboController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ComboController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ComboController@edit')->name('edit');
        Route::post('delete', 'ComboController@delete')->name('delete');
        Route::post('bulk-delete', 'ComboController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ComboController@change_status')->name('change.status');
    });
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('comboimage', 'ComboImageController@index')->name('comboimage');
    Route::group(['prefix' => 'comboimage', 'as'=>'comboimage.'], function () {
        Route::post('datatable-data', 'ComboImageController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ComboImageController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ComboImageController@edit')->name('edit');
        Route::post('delete', 'ComboImageController@delete')->name('delete');
        Route::post('bulk-delete', 'ComboImageController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ComboImageController@change_status')->name('change.status');
    });
});