<?php

use Illuminate\Http\Request;

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

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout');
Route::get('exporttofile','IssueController@exportDataToFile');

//Route::middleware('auth:api')
//	->get('/user', function (Request $request) {
//		return $request->user();
//	});

Route::group(['middleware' => 'auth:api'], function() {

	
//	Route::get('issues/{id}', 'IssueController@show');
	Route::post('issues', 'IssueController@createIssue');
	Route::get('issues', 'IssueController@manage');
	Route::put('issues/{id}', 'IssueController@updateIssue');
	Route::get('issues/openissues', 'IssueController@getAllOpenIssues');
	Route::get('issues/openissues/location/{locationId}', 'IssueController@getIssueByLocation');
	Route::get('issueitems','IssueController@getIssueItems');
	Route::get('locations','IssueController@getAllLocations');
	Route::get('storefeatures','IssueController@getAllStoreFeature');
	
	Route::get('getallfilterItems', 'IssueController@getAllFilterItems');
	
	
});
