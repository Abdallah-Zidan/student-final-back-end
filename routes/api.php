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

	Route::group(['prefix' => 'posts', 'namespace' => 'Post', 'middleware' => ['verified']], function () {
		Route::get('scope', 'ScopeController@index')->name('post.scope.index');

		Route::get('/', 'PostController@index')->middleware('auth:sanctum')->name('post.index');
		Route::post('/', 'PostController@store')->middleware('auth:sanctum')->name('post.store');
		Route::put('/{post}', 'PostController@update')->middleware('auth:sanctum')->name('post.update');
		Route::delete('/{post}', 'PostController@destroy')->middleware('auth:sanctum')->name('post.destroy');

		Route::get('/{post}/comments', 'CommentController@index')->middleware('auth:sanctum')->name('post.comments.index');
		Route::post('/{post}/comments/', 'CommentController@store')->middleware('auth:sanctum')->name('post.comments.store');
		Route::put('/{post}/comments/{comment}', 'CommentController@update')->middleware(['auth:sanctum', 'commentOwner'])->name('post.comments.update');
		Route::delete('/{post}/comments/{comment}', 'CommentController@destroy')->middleware(['auth:sanctum', 'commentOwner'])->name('post.comments.destroy');

		Route::get('/{post}/comments/{comment}/replies', 'ReplyController@index')->middleware('auth:sanctum')->name('post.comment.replies.index');
		Route::post('/{post}/comments/{comment}/replies', 'ReplyController@store')->middleware('auth:sanctum')->name('post.comment.replies.store');
		Route::put('/{post}/comments/{comment}/replies/{reply}', 'ReplyController@update')->middleware(['auth:sanctum', 'replyOwner'])->name('post.comment.replies.update');
		Route::delete('/{post}/comments/{comment}/replies/{reply}', 'ReplyController@destroy')->middleware(['auth:sanctum', 'replyOwner'])->name('post.comment.replies.destroy');
	});
});