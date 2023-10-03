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
    Route::get('gcategory', 'GalleryCategoryController@index')->name('gcategory');
    Route::group(['prefix' => 'gcategory', 'as'=>'gcategory.'], function () {
        Route::post('datatable-data', 'GalleryCategoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'GalleryCategoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'GalleryCategoryController@edit')->name('edit');
        Route::post('delete', 'GalleryCategoryController@delete')->name('delete');
        Route::post('bulk-delete', 'GalleryCategoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'GalleryCategoryController@change_status')->name('change.status');
    });
});

