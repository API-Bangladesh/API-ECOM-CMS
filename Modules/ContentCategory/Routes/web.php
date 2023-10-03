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
    Route::get('ccategory', 'ContentCategoryController@index')->name('ccategory');
    Route::group(['prefix' => 'ccategory', 'as'=>'ccategory.'], function () {
        Route::post('datatable-data', 'ContentCategoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ContentCategoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ContentCategoryController@edit')->name('edit');
        Route::post('delete', 'ContentCategoryController@delete')->name('delete');
        Route::post('bulk-delete', 'ContentCategoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ContentCategoryController@change_status')->name('change.status');
    });
});
