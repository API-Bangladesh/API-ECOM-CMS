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
    Route::get('scategory', 'SubCategoryController@index')->name('scategory');
    Route::group(['prefix' => 'scategory', 'as'=>'scategory.'], function () {
        Route::post('datatable-data', 'SubCategoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'SubCategoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'SubCategoryController@edit')->name('edit');
        Route::post('delete', 'SubCategoryController@delete')->name('delete');
        Route::post('bulk-delete', 'SubCategoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'SubCategoryController@change_status')->name('change.status');
    });
});

