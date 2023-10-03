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
    Route::get('dcategory', 'DocumentCategoryController@index')->name('dcategory');
    Route::group(['prefix' => 'dcategory', 'as'=>'dcategory.'], function () {
        Route::post('datatable-data', 'DocumentCategoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'DocumentCategoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'DocumentCategoryController@edit')->name('edit');
        Route::post('delete', 'DocumentCategoryController@delete')->name('delete');
        Route::post('bulk-delete', 'DocumentCategoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'DocumentCategoryController@change_status')->name('change.status');
    });
});
