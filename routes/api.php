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

	Route::get('universities', 'UniversityController@index')->name('university.index');

	Route::group(['prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth:sanctum', 'verified']], function () {
		Route::get('profile', 'ProfileController@show')->name('user.profile.show');
		Route::put('profile', 'ProfileController@update')->name('user.profile.update');
		Route::get('departments', 'DepartmentFacultyController@index')->name('user.department.index');
	});

	$groups = function () {
		Route::group(['namespace' => 'Post'], function () {
			Route::resource('posts', 'PostController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('posts.files', 'FileController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('posts.comments', 'CommentController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('posts.comments.replies', 'ReplyController')->only(['index', 'store', 'show', 'update', 'destroy']);
		});
		Route::group(['namespace' => 'Event'], function () {
			Route::resource('events', 'EventController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('events.files', 'FileController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('events.comments', 'CommentController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('events.comments.replies', 'ReplyController')->only(['index', 'store', 'show', 'update', 'destroy']);
		});
	};

	Route::group(['prefix' => 'departments/{department_faculty}', 'middleware' => ['auth:sanctum', 'verified']], function () {
		Route::group(['namespace' => 'Post'], function () {
			Route::resource('posts', 'PostController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('posts.files', 'FileController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('posts.comments', 'CommentController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('posts.comments.replies', 'ReplyController')->only(['index', 'store', 'show', 'update', 'destroy']);
		});
	});
	Route::group(['prefix' => 'faculties/{faculty}', 'middleware' => ['auth:sanctum', 'verified']], $groups);
	Route::group(['prefix' => 'universities/{university}', 'middleware' => ['auth:sanctum', 'verified']], $groups);
	Route::group(['prefix' => 'all/{empty?}', 'middleware' => ['auth:sanctum', 'verified']], function () {
		Route::group(['namespace' => 'Event'], function () {
			Route::resource('events', 'EventController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('events.files', 'FileController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('events.comments', 'CommentController')->only(['index', 'store', 'show', 'update', 'destroy']);
			Route::resource('events.comments.replies', 'ReplyController')->only(['index', 'store', 'show', 'update', 'destroy']);
		});
	});

	Route::group(['namespace' => 'Tool'], function () {
		Route::resource('needs', 'ToolController')->only(['index', 'store', 'show', 'update', 'destroy']);
		Route::resource('needs.files', 'FileController')->only(['index', 'store', 'show', 'update', 'destroy']);
		Route::resource('needs.comments', 'CommentController')->only(['index', 'store', 'show', 'update', 'destroy']);
		Route::resource('needs.comments.replies', 'ReplyController')->only(['index', 'store', 'show', 'update', 'destroy']);

		Route::resource('offers', 'ToolController')->only(['index', 'store', 'show', 'update', 'destroy']);
		Route::resource('offers.files', 'FileController')->only(['index', 'store', 'show', 'update', 'destroy']);
		Route::resource('offers.comments', 'CommentController')->only(['index', 'store', 'show', 'update', 'destroy']);
		Route::resource('offers.comments.replies', 'ReplyController')->only(['index', 'store', 'show', 'update', 'destroy']);
	});
});