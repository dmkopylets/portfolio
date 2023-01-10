<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Courses\Course;
use App\Models\Courses\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        $cources = [
            ['name' => 'math', 'description' => 'A degree in mathematics can lead to a job as an actuarial scientist or research analyst,...'],
            ['name' => 'biology', 'description' => 'Biologists also lead efforts to discover new and improved ways to clean and preserve the environment as well as alternative sources of energy, such as biofuels and biomass.'],
            ['name' => 'accounting', 'description' => 'Take classes in financial accounting, payroll accounting, individual and business tax accounting, tax research, business software packages and business writing'],
            ['name' => 'art', 'description' => 'Courses offered by the Art Department at Harper College transfer to state universities in Illinois and most private institutions, colleges and universities nationally, with few or no lost credits.'],
            ['name' => 'chem', 'description' => 'Chemists study the structures, compositions, reactions and other properties of substances and experiment with the laws that govern them'],
            ['name' => 'geol', 'description' => 'Earth sciences is the study of our planet, its resources'],
            ['name' => 'hist', 'description' => 'From the pyramids of ancient Egypt to the capitol building in Springfield, our course offerings cover the globe, and will give you an understanding and appreciation for the major events that shaped'],
            ['name' => 'law', 'description' => 'While there are hundreds of potential career opportunities in the law enforcement, justice administration and forensics fields, each differ significantly in their specific areas of expertise'],
            ['name' => 'socio', 'description' => 'The study of human behavior, culture, life, communities, social interaction, and globalization all falls under sociology. Students can learn key concepts for understanding and navigating an increasingly'],
            ['name' => 'mech', 'description' => 'Develop the skills and knowledge needed to begin a successful career in industrial electronics, facilities maintenance and other technology']
        ];
        foreach ($cources as $course) {
            $record = new Course;
            $record->name = $course['name'];
            $record->description = $course['description'];
            $record->save();
            $group = new Group;
            $group->name = 'Gr-' . substr(strval(100 + rand(1, 99)), 1, 2) . '-' . $course['name'];
            $group->save();
        }
    }
}
