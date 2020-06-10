<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your dashboard. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1/dashboard', 'namespace' => 'API\v1\Dashboard', 'as' => 'dashboard.', 'middleware' => ['auth:sanctum', 'verified']], function () {
	$methods = ['index', 'store', 'show', 'update', 'destroy'];

	Route::resource('universities', 'UniversityController')->only($methods);
	Route::resource('faculties', 'FacultyController')->only($methods);
	Route::resource('departments', 'DepartmentController')->only($methods);
	Route::post('departments/attach', 'DepartmentController@attach')->name('departments.attach');
	Route::post('departments/detach', 'DepartmentController@detach')->name('departments.detach');
	Route::resource('courses', 'CourseController')->only($methods);
	Route::post('courses/attach', 'CourseController@attach')->name('courses.attach');
	Route::post('courses/detach', 'CourseController@detach')->name('courses.detach');

	Route::resource('users', 'UserController')->only($methods);
	Route::post('users/departments/attach', 'UserController@attachDepartment')->name('users.departments.attach');
	Route::post('users/departments/detach', 'UserController@detachDepartment')->name('users.departments.detach');
	Route::post('users/courses/attach', 'UserController@attachCourse')->name('users.courses.attach');
	Route::post('users/courses/detach', 'UserController@detachCourse')->name('users.courses.detach');

	Route::resource('tags', 'TagController')->only($methods);
	Route::resource('posts', 'PostController')->only($methods);
	Route::resource('events', 'EventController')->only($methods);
	Route::resource('questions', 'QuestionController')->only($methods);
	Route::post('questions/attach', 'QuestionController@attach')->name('questions.attach');
	Route::post('questions/detach', 'QuestionController@detach')->name('questions.detach');
	Route::resource('tools', 'ToolController')->only($methods);
	Route::post('tools/attach', 'ToolController@attach')->name('tools.attach');
	Route::post('tools/detach', 'ToolController@detach')->name('tools.detach');
});