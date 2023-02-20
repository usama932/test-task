<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BathroomController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\TimeslotController;
use App\Http\Controllers\Admin\CleaningController;
use App\Http\Controllers\Admin\PaymentHistoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderExtraController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\HomeController;

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
Route::get('/', 'HomeController@index')->name('home');
Route::resource('check_orders','HomeController')->except('index');
Route::post('/discount/submit', 'HomeController@applydiscount')->name('discount.submit');

Route::get('/about-us', 'HomeController@getaboutus')->name('aboutus');

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/user', function () {
    return view('auth.user');
});
Route::get('/clear',function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
});

Auth::routes();

Route::group([
    'middleware'    => ['auth'],
    'prefix'        => 'client',
    'namespace'     => 'Client'
], function ()
{
    Route::get('/dashboard', 'ClientController@index')->name('client.dashboard');
	Route::get('/profile', 'ClientController@edit')->name('client-profile');
	Route::post('/admin-update', 'ClientController@update')->name('client-update');


});

Route::group([
    'middleware'    => ['auth','is_admin'],
    'prefix'        => 'admin',
    'namespace'     => 'Admin'
], function ()
{
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/profile', 'AdminController@edit')->name('admin-profile');
    Route::post('/admin-update', 'AdminController@update')->name('admin-update');
    //Setting Routes
    Route::resource('setting','SettingController');

	//User Routes
	Route::resource('clients','ClientController');
	Route::post('get-clients', 'ClientController@getClients')->name('admin.getClients');
	Route::post('get-client', 'ClientController@clientDetail')->name('admin.getClient');
	Route::get('client/delete/{id}', 'ClientController@destroy');
	Route::post('delete-selected-clients', 'ClientController@deleteSelectedClients')->name('admin.delete-selected-clients');

    //Rooms
    Route::resource('rooms','RoomController');
    Route::post('get-rooms', 'RoomController@getRooms')->name('admin.getRooms');
    Route::post('get-room', 'RoomController@RoomDetail')->name('admin.getRoom');
    Route::get('room/delete/{id}', 'RoomController@destroy');
    Route::post('delete-selected-rooms', 'RoomController@deleteSelectedRooms')->name('admin.delete-selected-rooms');

    //Bathrooms
    Route::resource('bathrooms','BathroomController');
    Route::post('get-bathrooms', 'BathroomController@getbathRooms')->name('admin.getbathRooms');
    Route::post('get-bathroom', 'BathroomController@bathRoomDetail')->name('admin.getbathRoom');
    Route::get('bathroom/delete/{id}', 'BathroomController@destroy');
    Route::post('delete-selected-bathrooms', 'BathroomController@deleteSelectedbathRooms')->name('admin.delete-selected-bathrooms');
    
    //Extra-Services
    Route::resource('services','ServicesController');
    Route::post('get-services', 'ServicesController@getservices')->name('admin.getServices');
    Route::post('get-service', 'ServicesController@bathServiceDetail')->name('admin.getService');
    Route::get('service/delete/{id}', 'ServicesController@destroy');
    Route::post('delete-selected-services', 'ServicesController@deleteSelectedbathRooms')->name('admin.delete-selected-services');

    //Discount
    Route::resource('discounts','DiscountController');
    Route::post('get-discounts', 'DiscountController@getdiscounts')->name('admin.getDiscounts');
    Route::post('get-discount', 'DiscountController@getdisountDetail')->name('admin.getDiscount');
    Route::get('discount/delete/{id}', 'DiscountController@destroy');
    Route::post('delete-selected-discounts', 'DiscountController@deleteSelectedDiscount')->name('admin.delete-selected-discounts');

    //time_slots
    Route::resource('time_slots','TimeslotController');
    Route::post('get-timeslots', 'TimeslotController@gettimeslots')->name('admin.getTimeslots');
    Route::post('get-timeslot', 'TimeslotController@gettimeslot')->name('admin.getTimeslot');
    Route::get('timeslot/delete/{id}', 'TimeslotController@destroy');
    Route::post('delete-selected-timeslots', 'TimeslotController@deleteSelectedTimeslot')->name('admin.delete-selected-timeslots');

    //Cleaning Types
    Route::resource('cleaning_types','CleaningController');
    Route::post('get-cleaningtypes', 'CleaningController@getcleaningtypes')->name('admin.getCleaningtypes');
    Route::post('get-cleaningtype', 'CleaningController@getcleaningtype')->name('admin.getCleaningtype');
    Route::get('cleaningtype/delete/{id}', 'CleaningController@destroy');
    Route::post('delete-selected-cleaningtypes', 'CleaningController@deleteSelectedCleaningTypes')->name('admin.delete-selected-cleaningtypes');

    //Payment Histories
    Route::resource('payment_histories','PaymentHistoryController');
    Route::post('get-payment_histories', 'PaymentHistoryController@getpayments')->name('admin.getpayments');
    Route::post('get-payment_history', 'PaymentHistoryController@getpayment')->name('admin.getpayment');
    Route::get('payment/delete/{id}', 'PaymentHistoryController@destroy');
    Route::post('delete-selected-payments', 'PaymentHistoryController@deleteSelectedpayments')->name('admin.delete-selected-payments');

    //Extra Orders
    Route::resource('extra_orders','OrderExtraController');
    Route::post('get-extra_orders', 'OrderExtraController@getextraorders')->name('admin.getextraorders');
    Route::post('get-extra_order', 'OrderExtraController@getextraorder')->name('admin.getextraorder');
    Route::get('extra_order/delete/{id}', 'OrderExtraController@destroy');
    Route::post('delete-selected-extra_order', 'OrderExtraController@deleteSelectedextra_orders')->name('admin.delete-selected-extra_orders');

    //Orders
    Route::resource('orders','OrderController');
    Route::post('get-orders', 'OrderController@getorders')->name('admin.getorders');
    Route::post('get-order', 'OrderController@getorder')->name('admin.getorder');
    Route::get('order/delete/{id}', 'OrderController@destroy');
    Route::get('order/detail/{id}', 'OrderController@order_detail')->name("order.detail");
    Route::post('delete-selected-order', 'OrderController@deleteSelectedorders')->name('admin.delete-selected-orders');
    //
    Route::resource('pages','PagesController');
    Route::resource('sections','SectionController');
    Route::post('get-pages', 'PagesController@getPages')->name('admin.getPages');
	Route::post('get-page', 'PagesController@getPage')->name('admin.getPage');
	Route::get('page/delete/{id}', 'PagesController@destroy');
	Route::post('delete-selected-page', 'PagesController@deleteSelectedPages')->name('admin.delete-selected-page');

    
});

