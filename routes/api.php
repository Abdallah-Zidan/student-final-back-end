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

// Add resources for posts, comments etc
// Add pageination for posts
Route::group(['prefix' => 'v1', 'namespace' => 'API\v1'], function () {
	Route::group(['namespace' => 'Auth'], function () {
		Route::post('/login', 'AuthController@login');
		Route::post('/register', 'AuthController@register');
		Route::post('/logout', 'AuthController@logout')->middleware('auth:sanctum');
		Route::get('/email/resend', 'VerificationController@resend')->name('verification.resend');
		Route::get('/email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
	});

	Route::group(['prefix'=>'profile', 'middleware' => ['auth:sanctum', 'verified']], function() {
		Route::get('/', 'ProfileController@show')->name('profile.show');
		Route::put('/', 'ProfileController@update')->name('profile.update');
	});

	Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum', 'verified']], function () {
		Route::get('post', 'PostController@index')->name('user.post.index');
		Route::get('department', 'DepartmentFacultyController@index')->name('user.department.index');
	});
});