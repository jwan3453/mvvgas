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

use Twilio\Rest\Client;

Route::get('/', function () {

});

Route::get('/login', function(){
	return view('login');
});

Route::get('/adminoverview', function(){
	return view('admin.adminoverview');
	
});

Route::get('/adminlocationview/{store}', function ($store){
    return view('admin.store')->with('store',$store);
});

Route::get('/closedissue', function(){
	return view('admin.closedissueview');
});