<?php

use App\Http\Controllers\AppsController;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Benchmark;


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

// Auth::routes();

Route::get('/', function () {
    return redirect('login');
});

// Route::get('/qr', [App\Http\Controllers\API\APIUserController::class, 'sendQR']);

Auth::routes(['register' => false]);

// Auth::routes(['verify' => true]);
/* Route Dashboards */
// Route::group(['prefix' => 'dashboard'], function () {
//     Route::get('analytics', [DashboardController::class, 'dashboardAnalytics'])->name('dashboard-analytics');
//     Route::get('ecommerce', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard-ecommerce');
// });

// Route::get('/', [StaterkitController::class, 'home'])->name('home');
// Route::get('home', [StaterkitController::class, 'home'])->name('home');
// Route Components

Route::get('layouts/collapsed-menu', [StaterkitController::class, 'collapsed_menu'])->name('collapsed-menu');
Route::get('layouts/full', [StaterkitController::class, 'layout_full'])->name('layout-full');
Route::get('layouts/without-menu', [StaterkitController::class, 'without_menu'])->name('without-menu');
Route::get('layouts/empty', [StaterkitController::class, 'layout_empty'])->name('layout-empty');
Route::get('layouts/blank', [StaterkitController::class, 'layout_blank'])->name('layout-blank');

/* Route Apps */
Route::group(['middleware' => 'auth','prefix' => 'admin'], function () {
    Route::resource('/users', App\Http\Controllers\UserController::class);
    Route::get('/users-status/{id}', [App\Http\Controllers\UserController::class, 'status'])->name('users.status');
    Route::get('/attendee-list', [App\Http\Controllers\UserController::class,'attendee_info'])->name('passenger.filter');
    Route::post('update-password/{id}/update', [App\Http\Controllers\UserController::class, 'update_password'])->name('user.update-password');
    Route::get('/home', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard-ecommerce');
    Route::resource('/county', App\Http\Controllers\CountyController::class);
    Route::get('/county-status/{id}', [App\Http\Controllers\CountyController::class, 'status'])->name('county.status');
    Route::resource('/route', App\Http\Controllers\RouteController::class);
    Route::get('/route-status/{id}', [App\Http\Controllers\RouteController::class, 'status'])->name('route.status');
    Route::get('/route-point', [App\Http\Controllers\RouteController::class, 'get_pickuppoint'])->name('route-point.get_pickuppoint');
    Route::resource('/pickup-point', App\Http\Controllers\PickuppointController::class);
    Route::get('/pickup-point-status/{id}', [App\Http\Controllers\PickuppointController::class, 'status'])->name('pickup-point.status');
    Route::post('/pickup-point-get', [App\Http\Controllers\PickuppointController::class, 'get_pickup_point'])->name('pickup.get');
    Route::resource('/bus', App\Http\Controllers\BusController::class);
    Route::get('/bus-status/{id}', [App\Http\Controllers\BusController::class, 'status'])->name('bus.status');
    Route::resource('/banner', App\Http\Controllers\SliderController::class);
    Route::get('/banner-status/{id}', [App\Http\Controllers\SliderController::class, 'status'])->name('banner.status');
    Route::resource('/coupon', App\Http\Controllers\CouponController::class);
    Route::get('/coupon-status/{id}', [App\Http\Controllers\CouponController::class, 'status'])->name('coupon.status');
    Route::resource('/seo', App\Http\Controllers\SeoController::class);
    Route::put('/seo-meta/{id}', [App\Http\Controllers\SeoController::class, 'update_metaformula'])->name('seo.metaformula');
    Route::get('/seo-status/{id}', [App\Http\Controllers\SeoController::class, 'status'])->name('seo.status');
    Route::resource('/booking', App\Http\Controllers\BookingController::class);
    Route::get('/attendee-info/{id}', [App\Http\Controllers\BookingController::class, 'attendee_info'])->name('attendee.info');
    Route::get('county-list', [CountyController::class, 'county_list'])->name('app-county-list');
    Route::resource('/event', App\Http\Controllers\ProductController::class);
    Route::get('/event-status/{id}', [App\Http\Controllers\ProductController::class, 'status'])->name('event.status');
    Route::get('/event-variation', [App\Http\Controllers\ProductController::class, 'product_variation'])->name('event-variation.variation');
    Route::get('/event-inventory', [App\Http\Controllers\ProductController::class, 'product_inventory'])->name('event-inventory.inventory');
    Route::post('/store-inventory', [App\Http\Controllers\ProductController::class, 'store_inventory'])->name('inventory.store');
    Route::put('/update-inventory', [App\Http\Controllers\ProductController::class, 'update_inventory'])->name('inventory.update');
    Route::resource('/assign', App\Http\Controllers\BusassignmentController::class);
    Route::get('assign-bus', [App\Http\Controllers\BusassignmentController::class, 'index'])->name('assign-bus.index');
    // Route::get('assign-bus-schedule/{id}', [App\Http\Controllers\BusassignmentController::class, 'add_schedule'])->name('schedule-bus.add_schedule');
    Route::get('assign-user', [App\Http\Controllers\BusassignmentController::class, 'assign_unique_user'])->name('assign.user');
    Route::post('/add-variation', [App\Http\Controllers\ProductController::class, 'add_variation_by_form'])->name('product.add_variation_by_form');
    Route::get('/add-variation/{id}', [App\Http\Controllers\ProductController::class, 'add_variation'])->name('product.add_variation');
    Route::resource('/category', App\Http\Controllers\CategoryController::class);
    Route::get('/category-status/{id}', [App\Http\Controllers\CategoryController::class, 'status'])->name('category.status');
    Route::resource('/operator', App\Http\Controllers\OperatorController::class);
    Route::get('/operator-status/{id}', [App\Http\Controllers\OperatorController::class, 'status'])->name('operator.status');
    Route::get('/operator-request', [App\Http\Controllers\OperatorController::class, 'opeartor_request'])->name('operator.request');
    Route::get('/admin-request', [App\Http\Controllers\OperatorController::class, 'admin_request'])->name('admin.request');
    Route::post('/request-submit-admin', [App\Http\Controllers\OperatorController::class, 'admin_request_submit'])->name('admin.request_submit');
    Route::get('/request-submit-operator/{id}', [App\Http\Controllers\OperatorController::class, 'operator_request'])->name('operator.request_submit');
    Route::get('/bus-list', [App\Http\Controllers\OperatorController::class, 'bus_list'])->name('bus.list');
    Route::get('user/list', [AppsController::class, 'user_list'])->name('app-user-list');
    Route::get('user/view', [AppsController::class, 'user_view'])->name('app-user-view');
    Route::get('user/edit', [AppsController::class, 'user_edit'])->name('app-user-edit');
});

Route::stripeWebhooks('/payment-webhooks');

// Route::get('inspiring', function () {
//     $data=['data'=>Inspiring::quote()];
//     return response()->json($data, 200);
// });

/* Route Apps */
// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);