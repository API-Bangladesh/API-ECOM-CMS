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
    Route::get('segment', 'SegmentController@index')->name('segment');
    Route::group(['prefix' => 'segment', 'as'=>'segment.'], function () {
        Route::post('datatable-data', 'SegmentController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'SegmentController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'SegmentController@edit')->name('edit');
        Route::post('delete', 'SegmentController@delete')->name('delete');
        Route::post('bulk-delete', 'SegmentController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'SegmentController@change_status')->name('change.status');
    });
});
