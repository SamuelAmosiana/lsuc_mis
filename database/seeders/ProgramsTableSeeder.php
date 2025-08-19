<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'code' => 'BSC-CS',
                'name' => 'Bachelor of Science in Computer Science',
                'description' => 'A comprehensive program covering computer science fundamentals and advanced topics.',
                'duration_years' => 4,
                'total_semesters' => 8,
                'total_credits' => 120,
                'fee_per_semester' => 2500.00,
                'degree_awarded' => 'Bachelor of Science',
                'department' => 'Computer Science',
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'code' => 'BBA',
                'name' => 'Bachelor of Business Administration',
                'description' => 'A program focused on business administration and management principles.',
                'duration_years' => 4,
                'total_semesters' => 8,
                'total_credits' => 120,
                'fee_per_semester' => 2200.00,
                'degree_awarded' => 'Bachelor of Business Administration',
                'department' => 'Business Administration',
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'code' => 'LLB',
                'name' => 'Bachelor of Laws',
                'description' => 'A professional degree in law preparing students for legal practice.',
                'duration_years' => 4,
                'total_semesters' => 8,
                'total_credits' => 144,
                'fee_per_semester' => 2800.00,
                'degree_awarded' => 'Bachelor of Laws',
                'department' => 'Law',
                'is_active' => true,
                'created_by' => 1,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
