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
		Route::post('login', 'AuthController@login')->name('login');
		Route::post('register', 'AuthController@register')->name('register');
		Route::post('logout', 'AuthController@logout')->name('logout')->middleware(['auth:sanctum', 'verified']);
		Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
		Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
	});

	Route::get('universities', 'UniversityController@index')->name('university.index');

	Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
		Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
			Route::get('profile', 'ProfileController@show')->name('user.profile.show');
			Route::put('profile', 'ProfileController@update')->name('user.profile.update');
			Route::get('departments', 'DepartmentFacultyController@index')->name('user.department.index');
		});

		$methods = ['index', 'store', 'show', 'update', 'destroy'];

		Route::resource('posts', 'PostController')->only($methods);
		Route::post('posts/report', 'PostController@report')->name('posts.report');
		Route::resource('events', 'EventController')->only($methods);
		Route::post('events/{event}/interests', 'InterestController@store')->name('interests.store');
		Route::delete('events/{event}/interests', 'InterestController@destroy')->name('interests.destroy');
		Route::resource('questions', 'QuestionController')->only($methods);
		Route::resource('tools', 'ToolController')->only($methods);
		Route::post('tools/close', 'ToolController@close')->name('tools.close');
		Route::get('tags', 'TagController@index')->name('tags.index');

		Route::resource('posts.files', 'FileController')->only($methods);
		Route::resource('events.files', 'FileController')->only($methods);
		Route::resource('tools.files', 'FileController')->only($methods);

		Route::resource('posts.comments', 'CommentController')->only($methods);
		Route::resource('events.comments', 'CommentController')->only($methods);
		Route::resource('questions.comments', 'CommentController')->only($methods);
		Route::resource('tools.comments', 'CommentController')->only($methods);

		Route::resource('comments.replies', 'ReplyController')->only($methods);

		Route::post('comments/{comment}/rates', 'RateController@store')->name('rates.store');
		Route::put('comments/{comment}/rates', 'RateController@update')->name('rates.update');
		Route::delete('comments/{comment}/rates', 'RateController@destroy')->name('rates.destroy');
	});
});