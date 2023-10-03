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
    Route::get('bcategory', 'BlogCategoryController@index')->name('bcategory');
    Route::group(['prefix' => 'bcategory', 'as'=>'bcategory.'], function () {
        Route::post('datatable-data', 'BlogCategoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BlogCategoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BlogCategoryController@edit')->name('edit');
        Route::post('delete', 'BlogCategoryController@delete')->name('delete');
        Route::post('bulk-delete', 'BlogCategoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'BlogCategoryController@change_status')->name('change.status');
    });
});

