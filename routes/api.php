<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1', 'namespace' => 'API\v1'], function () {
	Route::group(['namespace' => 'Auth'], function () {
		Route::post('/login', 'AuthController@login');
		Route::post('/register', 'AuthController@register');
		Route::post('/logout', 'AuthController@logout')->middleware('auth:sanctum');
		Route::get('/email/resend', 'VerificationController@resend')->name('verification.resend');
		Route::get('/email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
	});

	Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
		Route::get('university', 'UniversityController@index')->name('university.index');
	});

	Route::group(['prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth:sanctum', 'verified']], function () {
		Route::get('profile', 'ProfileController@show')->name('user.profile.show');
		Route::put('profile', 'ProfileController@update')->name('user.profile.update');
		Route::get('department', 'DepartmentFacultyController@index')->name('user.department.index');
	});

	Route::group(['prefix' => 'post', 'namespace' => 'Post', 'middleware' => ['verified']], function () {
		Route::get('scope', 'ScopeController@index')->name('post.scope.index');
		Route::post('/', 'PostController@index')->middleware('auth:sanctum')->name('post.index');
	});
});