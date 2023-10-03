<?php

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
    Route::get('inventory', 'InventoryController@index')->name('inventory');
    Route::group(['prefix' => 'inventory', 'as'=>'inventory.'], function () {
        Route::post('datatable-data', 'InventoryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'InventoryController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'InventoryController@edit')->name('edit');
        Route::post('delete', 'InventoryController@delete')->name('delete');
        Route::post('bulk-delete', 'InventoryController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'InventoryController@change_status')->name('change.status');
    });
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('inventoryimage', 'InventoryImageController@index')->name('inventoryimage');
    Route::group(['prefix' => 'inventoryimage', 'as'=>'inventoryimage.'], function () {
        Route::post('datatable-data', 'InventoryImageController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'InventoryImageController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'InventoryImageController@edit')->name('edit');
        Route::post('delete', 'InventoryImageController@delete')->name('delete');
        Route::post('bulk-delete', 'InventoryImageController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'InventoryImageController@change_status')->name('change.status');
    });
});
