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
    Route::get('pimage', 'ProductImageController@index')->name('pimage');
    Route::group(['prefix' => 'pimage', 'as'=>'pimage.'], function () {
        Route::post('datatable-data', 'ProductImageController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ProductImageController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ProductImageController@edit')->name('edit');
        Route::post('delete', 'ProductImageController@delete')->name('delete');
        Route::post('bulk-delete', 'ProductImageController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ProductImageController@change_status')->name('change.status');
    });
});
