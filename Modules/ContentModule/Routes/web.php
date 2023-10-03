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
    Route::get('cmodule', 'ContentModuleController@index')->name('cmodule');
    Route::group(['prefix' => 'cmodule', 'as'=>'cmodule.'], function () {
        Route::post('datatable-data', 'ContentModuleController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ContentModuleController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ContentModuleController@edit')->name('edit');
        Route::post('delete', 'ContentModuleController@delete')->name('delete');
        Route::post('bulk-delete', 'ContentModuleController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ContentModuleController@change_status')->name('change.status');
        Route::post('module-field-change-status', 'ContentModuleController@module_field_change_status')->name('module.field.change.status');
    });
});

