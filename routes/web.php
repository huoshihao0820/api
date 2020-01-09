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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test/pay','TestController@alipay');        //去支付
Route::get('/test/alipay/return','Alipay\PayController@aliReturn');
Route::post('/test/alipay/notify','Alipay\PayController@notify');
Route::post('/test/register','TestController@register');
Route::post('/test/login','TestController@login');
Route::get('/test/list','TestController@userList')->middleware('fileter');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test/pubkey','HomeController@pubkey');
Route::any('/test/encrypt','HomeController@encrypt');
Route::post('/test/pubkey_do','HomeController@pubkey_do');
Route::post('/test/encrypt_do','HomeController@encrypt_do');


