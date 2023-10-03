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
    Route::get('email', 'MailSettingsController@index')->name('email');
    Route::group(['prefix' => 'email', 'as'=>'email.'], function () {
        Route::post('datatable-data', 'MailSettingsController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MailSettingsController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'MailSettingsController@edit')->name('edit');
        Route::post('delete', 'MailSettingsController@delete')->name('delete');
        Route::post('bulk-delete', 'MailSettingsController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'MailSettingsController@change_status')->name('change.status');
    });
});

