<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolProgrammeCourseSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            'School of Business',
            'School of ICT',
            'School of Hospitality',
        ];

        $schoolIds = [];
        foreach ($schools as $name) {
            DB::table('school')->updateOrInsert(['name' => $name], []);
            $schoolIds[$name] = DB::table('school')->where('name', $name)->value('school_id');
        }

        $programmes = [
            ['name' => 'Diploma in Business Administration', 'school' => 'School of Business'],
            ['name' => 'Diploma in Accounting', 'school' => 'School of Business'],
            ['name' => 'Diploma in Information Technology', 'school' => 'School of ICT'],
            ['name' => 'Diploma in Computer Science', 'school' => 'School of ICT'],
            ['name' => 'Diploma in Hospitality Management', 'school' => 'School of Hospitality'],
        ];

        $programmeIds = [];
        foreach ($programmes as $programme) {
            DB::table('programme')->updateOrInsert(
                ['name' => $programme['name']],
                ['school_id' => $schoolIds[$programme['school']] ?? null]
            );
            $programmeIds[$programme['name']] = DB::table('programme')->where('name', $programme['name'])->value('programme_id');
        }

        $courses = [
            ['name' => 'Principles of Management', 'programme' => 'Diploma in Business Administration'],
            ['name' => 'Financial Accounting I', 'programme' => 'Diploma in Accounting'],
            ['name' => 'Database Systems', 'programme' => 'Diploma in Information Technology'],
            ['name' => 'Data Structures', 'programme' => 'Diploma in Computer Science'],
            ['name' => 'Food & Beverage Operations', 'programme' => 'Diploma in Hospitality Management'],
        ];

        foreach ($courses as $course) {
            DB::table('course')->updateOrInsert(
                ['name' => $course['name']],
                ['programme_id' => $programmeIds[$course['programme']] ?? null]
            );
        }
    }
}


