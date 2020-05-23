<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(UniversitySeeder::class);
        $this->call(FacultySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DepartmentFacultySeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(CourseDepartmentFacultySeeder::class);
        $this->call(DepartmentFacultyUserSeeder::class);
        $this->call(CourseDepartmentFacultyUserSeeder::class);
    }
}