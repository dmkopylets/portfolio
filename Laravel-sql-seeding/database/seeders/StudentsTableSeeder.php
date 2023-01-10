<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Courses\Course;
use App\Models\Courses\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::factory()->count(20)->create();

        $firstNameCollection = Student::select('first_name')->pluck('first_name')->toArray();
        $lastNameCollection = Student::select('last_name')->pluck('last_name')->toArray();
        $i = 0;
        while ($i < 180){
            $newFirstName = $firstNameCollection[rand(0, 19)];
            $newLastName = $lastNameCollection[rand(0, 19)];
            $record = new Student();
            $record->first_name =  $newFirstName;
            $record->last_name = $newLastName;
            if ($this->ifNotExist($newFirstName, $newLastName)){
                $record->save();
                $i++;
            }
        }
    }

    private function ifNotExist(string $firstName, string $lastName): bool
    {
        $combination = Student::where('first_name', '=', $firstName)->where('last_name', '=', $lastName)->first();
        return !$combination;
    }
}
