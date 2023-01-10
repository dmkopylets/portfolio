<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Courses\Course;
use App\Models\Courses\Student;
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
        $this->call(CoursesTableSeeder::class);
        $this->call(StudentsTableSeeder::class);

        $rand = rand(10, 30);
        $students = Student::all();
        Course::all()->each(function ($course) use ($students, $rand) {
            $course->students()->attach(
                $students->random($rand)->pluck('id')->toArray()
            );
        });
    }
}
