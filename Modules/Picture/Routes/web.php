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
    Route::get('picture', 'PictureController@index')->name('picture');
    Route::group(['prefix' => 'picture', 'as'=>'picture.'], function () {
        Route::post('datatable-data', 'PictureController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'PictureController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'PictureController@edit')->name('edit');
        Route::post('delete', 'PictureController@delete')->name('delete');
        Route::post('bulk-delete', 'PictureController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'PictureController@change_status')->name('change.status');
    });
});
