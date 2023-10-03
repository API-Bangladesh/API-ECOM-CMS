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
    Route::get('variant', 'VariantController@index')->name('variant');
    Route::group(['prefix' => 'variant', 'as'=>'variant.'], function () {
        Route::post('datatable-data', 'VariantController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'VariantController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'VariantController@edit')->name('edit');
        Route::post('delete', 'VariantController@delete')->name('delete');
        Route::post('bulk-delete', 'VariantController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'VariantController@change_status')->name('change.status');
    });
});

