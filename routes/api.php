<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Customer\Entities\Customer;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 *
 */
Route::post('/register', function (Request $request) {
    try {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return make_validation_error_response($validator->getMessageBag());
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        return make_success_response("Record saved successfully.");
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
});

/**
 *
 */
Route::post('/login', function (Request $request) {
    try {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return make_validation_error_response($validator->getMessageBag());
        }

        $customer = Customer::where('email', $request->email)->first();
        if (empty($customer)) throw new Exception("Customer not found.");

        if (Hash::check($request->password, $customer->password)) {
            $customer->api_token = Str::random(60);
            $customer->update();
        }

        return make_success_response("Login successfully.", [
            'api_token' => $customer->api_token
        ]);
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
});

/**
 *
 */
Route::post('/logout', function (Request $request) {
    try {
        $authorization = $request->header('authorization');

        if (empty($authorization)) {
            throw new Exception("Authorization token not found.");
        }

        $customer = Customer::where('api_token', $authorization)->first();
        if (empty($customer)) throw new Exception("Customer not found.");

        $customer->api_token = Null;
        $customer->update();

        return make_success_response("Logout successful.");
    } catch (Exception $exception) {
        return make_error_response($exception->getMessage());
    }
});
