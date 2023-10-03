<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\Entities\Category;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/category', function (Request $request) {
    return $request->user();
});

/**
 *
 */
Route::get('/categories', function (Request $request) {
    try {
        return Category::paginate();
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
});

/**
 *
 */
Route::get('/categories/{category}', function (Request $request, $id) {
    try {
        return Category::findOrFail($id);
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
});
