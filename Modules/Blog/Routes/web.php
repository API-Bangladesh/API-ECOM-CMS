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
    Route::get('blog', 'BlogController@index')->name('blog');
    Route::group(['prefix' => 'blog', 'as'=>'blog.'], function () {
        Route::post('datatable-data', 'BlogController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BlogController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BlogController@edit')->name('edit');
        Route::post('delete', 'BlogController@delete')->name('delete');
        Route::post('bulk-delete', 'BlogController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'BlogController@change_status')->name('change.status');
    });
});
