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
    Route::get('combocategory', 'ComboCategoryController@index')->name('combocategory');
    Route::group(['prefix' => 'combocategory', 'as'=>'combocategory.'], function () {
        Route::post('datatable-data', 'ComboCategoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ComboCategoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ComboCategoryController@edit')->name('edit');
        Route::post('delete', 'ComboCategoryController@delete')->name('delete');
        Route::post('bulk-delete', 'ComboCategoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ComboCategoryController@change_status')->name('change.status');
    });
});
