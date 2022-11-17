<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Mail\EventBooked;
use App\Models\Booking;
use App\Models\User;

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

//Event API's
Route::resource('/event', App\Http\Controllers\API\EventController::class);

Route::get('/event-detail/{id}', [App\Http\Controllers\API\EventController::class, 'product_detail']);

Route::get('/event-search/{search}', [App\Http\Controllers\API\EventController::class, 'search_product']);

Route::resource('/event', App\Http\Controllers\API\EventController::class);

//Event Slider

Route::get('/get_slider', [App\Http\Controllers\API\SliderController::class, 'get_slider']);

//Event SEO

Route::resource('/seo', App\Http\Controllers\API\SeoController::class);

//Stripe Payment API 

Route::post('/intent-payments', [App\Http\Controllers\API\StripeController::class, 'StripePaymentIntent']);

// Event County
Route::get('get_county/{id}', [App\Http\Controllers\API\EventController::class, 'select_county_by_point']);

Route::get('county/{id}', [App\Http\Controllers\API\EventController::class, 'county_by_Id']);

// Event Price API's 
Route::get('/get_price/{product_id}/{county_id}/{pickup_id}', [App\Http\Controllers\API\EventController::class, 'get_price']);

// Event Coupon API's

Route::post('/coupon_code', [App\Http\Controllers\API\CouponController::class, 'validate_coupon']);

// User login

Route::post('/user/login', [App\Http\Controllers\API\APIUserController::class, 'userLogin']);

// User register

Route::post('/user/register', [App\Http\Controllers\API\APIUserController::class, 'store']);

// User reset password link

Route::post('/user/reset_password', [App\Http\Controllers\API\APIUserController::class, 'sendResetEmail']);



// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('user/{id}', [App\Http\Controllers\API\APIUserController::class, 'show']);

    Route::post('user/update/{id}', [App\Http\Controllers\API\APIUserController::class, 'update']);

    Route::post('user-password/update/{id}', [App\Http\Controllers\API\APIUserController::class, 'update_password']);

    Route::post('/user/update_reset_password', [App\Http\Controllers\API\APIUserController::class, 'reset_password']);

    Route::post('user/update_password/{id}', [App\Http\Controllers\API\APIUserController::class, 'update_password']);

    //User Order
    Route::get('/orders/{user_id}', [App\Http\Controllers\API\OrderController::class, 'orders_list']);

    Route::get('/orders_details/{booking_id}', [App\Http\Controllers\API\OrderController::class, 'orders_details']);

    //Driver API's

    Route::post('/driver/event_list', [App\Http\Controllers\API\DriverController::class, 'bus_event_lists']);

    Route::post('/driver/attendee_list', [App\Http\Controllers\API\DriverController::class, 'bus_attendee_list']);

    Route::post('/driver/attendee_details', [App\Http\Controllers\API\DriverController::class, 'attendee_details']);

    Route::post('/driver/check_in', [App\Http\Controllers\API\DriverController::class, 'ticket_checkIn']);
});

// Driver Login

Route::post('/driver/login', [App\Http\Controllers\API\DriverController::class, 'driver_login']);

// Route::post('user/forget-password',[App\Http\Controllers\API\APIUserController::class, 'user_update_password']);

Route::post('/intent-payment', [App\Http\Controllers\API\EventController::class, 'createPaymentIntent']);

Route::get('/mailsend', function (Request $request) {
    $users=[
        "name"=>"John doe",
        "email"=>'john@gmail.com',
        "phone"=>9900897867,
        "address"=>"901 street olive"
    ];

    $users=Booking::select('bookings.*','products.name')->join('products','products.id','=','bookings.product_id')->first();
    \Mail::to('bonoj33891@lidely.com')->send(new EventBooked($users));

    return response()->json([
        'status'=>200,
        'message'=>'mail send successfully'
    ], 200);
});
