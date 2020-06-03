<?php

namespace App\Providers;

use App\DepartmentFaculty;
use App\Event;
use App\Faculty;
use App\Post;
use App\Question;
use App\Tool;
use App\University;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Route::bind('department_faculty', function ($value) {
			return DepartmentFaculty::findOrFail($value);
		});

		Route::bind('faculty', function ($value) {
			return Faculty::findOrFail($value);
		});

		Route::bind('university', function ($value) {
			return University::findOrFail($value);
        });

        Route::bind('post', function ($value) {
            return Post::findOrFail($value);
        });

        Route::bind('event', function ($value) {
            return Event::findOrFail($value);
        });

        Route::bind('tool', function ($value) {
            return Tool::findOrFail($value);
        });

        Route::bind('question', function ($value) {
            return Question::findOrFail($value);
        });
    
    }
}