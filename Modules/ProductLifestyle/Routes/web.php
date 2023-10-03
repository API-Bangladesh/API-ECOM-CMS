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
    Route::get('limage', 'ProductLifestyleController@index')->name('limage');
    Route::group(['prefix' => 'limage', 'as'=>'limage.'], function () {
        Route::post('datatable-data', 'ProductLifestyleController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ProductLifestyleController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ProductLifestyleController@edit')->name('edit');
        Route::post('delete', 'ProductLifestyleController@delete')->name('delete');
        Route::post('bulk-delete', 'ProductLifestyleController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ProductLifestyleController@change_status')->name('change.status');
    });
});
