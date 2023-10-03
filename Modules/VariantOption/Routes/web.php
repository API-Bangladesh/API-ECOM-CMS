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
    Route::get('voption', 'VariantOptionController@index')->name('voption');
    Route::group(['prefix' => 'voption', 'as'=>'voption.'], function () {
        Route::post('datatable-data', 'VariantOptionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'VariantOptionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'VariantOptionController@edit')->name('edit');
        Route::post('delete', 'VariantOptionController@delete')->name('delete');
        Route::post('bulk-delete', 'VariantOptionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'VariantOptionController@change_status')->name('change.status');
    });
});
