<?php

namespace App\Providers;

use App\DepartmentFaculty;
use App\Faculty;
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
    }
}